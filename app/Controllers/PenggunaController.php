<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenggunaModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * PenggunaController - Controller untuk mengelola data pengguna sistem
 */
class PenggunaController extends BaseController
{
    protected PenggunaModel $modelPengguna;

    public function __construct()
    {
        $this->modelPengguna = new PenggunaModel();
    }

    /**
     * Tampilkan daftar pengguna
     */
    public function index()
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $this->setPageData('Manajemen Pengguna dan Manajemen Akses', 'Kelola pengguna dan hak akses sistem');

        $keyword = trim($this->request->getGet('q') ?? '');
        $filterRole = $this->request->getGet('role');

        $builder = $this->modelPengguna->select('users.*, roles.name as role')
                                       ->join('roles', 'roles.id = users.role_id', 'left')
                                       ->orderBy('users.name', 'ASC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('username', $keyword)
                ->groupEnd();
        }

        if ($filterRole && in_array($filterRole, ['superadmin', 'admin'], true)) {
            $builder->where('roles.name', $filterRole);
        }

        $users = $builder->findAll();

        return $this->render('users/index', [
            'users'      => $users,
            'keyword'    => $keyword,
            'filterRole' => $filterRole,
        ]);
    }

    /**
     * Form tambah pengguna baru
     */
    public function tambah()
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $this->setPageData('Tambah Pengguna', 'Tambah pengguna dan atur hak akses');

        return $this->render('users/create', [
            'user'       => ['username' => '', 'name' => '', 'role' => 'admin', 'is_active' => 1],
            'validation' => service('validation'),
        ]);
    }

    /**
     * Simpan pengguna baru
     */
    public function simpan()
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        if (!$this->validate([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]|alpha_numeric',
            'name'     => 'required|min_length[3]|max_length[100]',
            'password' => 'required|min_length[6]|max_length[255]',
            'role'     => 'required|in_list[superadmin,admin]',
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');
        $data = [
            'username'  => trim($this->request->getPost('username') ?? ''),
            'name'      => trim($this->request->getPost('name') ?? ''),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'   => $this->resolveRoleId($role),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->modelPengguna->insert($data)) {
            return redirect()->to('/users')->with('success', 'Perubahan telah disimpan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user');
    }

    /**
     * Form ubah/edit pengguna
     */
    public function ubah($id)
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->modelPengguna->select('users.*, roles.name as role')
                                    ->join('roles', 'roles.id = users.role_id', 'left')
                                    ->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        $this->setPageData('Ubah Pengguna dan Hak Akses', 'Edit: ' . $user['name']);

        return $this->render('users/edit', [
            'user'       => $user,
            'validation' => service('validation'),
        ]);
    }

    /**
     * Perbarui data pengguna
     */
    public function perbarui($id)
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->modelPengguna->select('users.*, roles.name as role')
                                    ->join('roles', 'roles.id = users.role_id', 'left')
                                    ->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]|alpha_numeric",
            'name'     => 'required|min_length[3]|max_length[100]',
            'role'     => 'required|in_list[superadmin,admin]',
        ];

        // Password opsional saat update
        $password = $this->request->getPost('password');
        if ($password) {
            $rules['password'] = 'min_length[6]|max_length[255]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');
        $data = [
            'username'  => trim($this->request->getPost('username') ?? ''),
            'name'      => trim($this->request->getPost('name') ?? ''),
            'role_id'   => $this->resolveRoleId($role),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->modelPengguna->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'Perubahan telah disimpan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user');
    }

    /**
     * Hapus pengguna
     */
    public function hapus($id): RedirectResponse
    {
        if (session('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $user = $this->modelPengguna->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }

        // Cegah menghapus akun sendiri
        if ((int) $id === (int) session('userId')) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($this->modelPengguna->delete($id)) {
            return redirect()->to('/users')->with('success', 'User berhasil dihapus');
        }

        return redirect()->to('/users')->with('error', 'Gagal menghapus user');
    }

    /**
     * Resolve role name ke role_id dari tabel roles
     */
    private function resolveRoleId(string $roleName): ?int
    {
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('name', $roleName)->get()->getRowArray();
        return $role ? (int) $role['id'] : null;
    }
}
