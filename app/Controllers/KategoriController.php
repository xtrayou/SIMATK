<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * KategoriController - Controller untuk mengelola kategori produk
 *
 * Relasi:
 * - Kategori memiliki banyak Produk
 */
class KategoriController extends BaseController
{
    protected KategoriModel $modelKategori;

    public function __construct()
    {
        $this->modelKategori = new KategoriModel();
    }

    /**
     * Ambil data form dan mapping ke array
     */
    private function getDataForm(): array
    {
        return [
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];
    }

    /**
     * Validasi request dan kembalikan redirect jika gagal
     */
    private function validasiRequest(array $aturan): ?RedirectResponse
    {
        if ($this->validate($aturan)) {
            return null;
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    /**
     * Cari kategori berdasarkan ID atau redirect jika tidak ditemukan
     */
    private function cariKategoriAtauRedirect(int $id)
    {
        $kategori = $this->modelKategori->find($id);

        if ($kategori) {
            return $kategori;
        }

        return redirect()->to('/categories')
            ->with('error', 'Kategori tidak ditemukan');
    }

    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $this->setPageData('Kategori', 'Manajemen Kategori Produk');

        $keyword      = trim((string) ($this->request->getGet('q') ?? ''));
        $filterStatus = $this->request->getGet('status');
        $perPage      = (int) ($this->request->getGet('per_page') ?? 10);
        $orderBy      = $this->request->getGet('urut') ?? 'name';
        $orderDir     = strtolower($this->request->getGet('arah') ?? 'asc') === 'desc' ? 'desc' : 'asc';
        $page         = (int) ($this->request->getGet('page') ?? 1);

        if (!in_array($orderBy, ['name', 'created_at', 'is_active'])) {
            $orderBy = 'name';
        }

        $totalData  = $this->modelKategori->hitungKategori($keyword, $filterStatus);
        $offset     = ($page - 1) * $perPage;
        $categories = $this->modelKategori->getKategoriDenganJumlahProduk(
            $keyword, $filterStatus, $orderBy, $orderDir, $perPage, $offset
        );

        $pager = service('pager');
        $pagination = $pager->makeLinks($page, $perPage, $totalData, 'default_full');

        return $this->render('kategori/index', [
            'daftarKategori' => $categories,
            'kataKunci'      => $keyword,
            'filterStatus'   => $filterStatus,
            'perHalaman'     => $perPage,
            'kolomUrut'      => $orderBy,
            'arahUrut'       => $orderDir,
            'totalData'      => $totalData,
            'nomorAwal'      => $offset + 1,
            'paginasi'       => $pagination,
        ]);
    }

    /**
     * Form tambah kategori baru
     */
    public function tambah()
    {
        $this->setPageData('Tambah Kategori', 'Buat kategori produk baru');

        return $this->render('kategori/create', [
            'kategori'   => ['name' => '', 'description' => '', 'is_active' => true],
            'validation' => service('validation'),
        ]);
    }

    /**
     * Simpan kategori baru
     */
    public function simpan()
    {
        $error = $this->validasiRequest([
            'name'        => 'required|min_length[3]|max_length[100]|is_unique[categories.name]',
            'description' => 'permit_empty|max_length[500]',
        ]);
        
        if ($error) return $error;

        if ($this->modelKategori->insert($this->getDataForm())) {
            return redirect()->to('/categories')->with('success', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori');
    }

    /**
     * Form ubah/edit kategori
     */
    public function ubah($id)
    {
        $category = $this->cariKategoriAtauRedirect((int) $id);
        if ($category instanceof RedirectResponse) return $category;

        $this->setPageData('Edit Kategori', 'Edit Kategori: ' . $category['name']);

        return $this->render('kategori/edit', [
            'kategori'   => $category,
            'validation' => service('validation'),
        ]);
    }

    /**
     * Perbarui data kategori
     */
    public function perbarui($id)
    {
        $category = $this->cariKategoriAtauRedirect((int) $id);
        if ($category instanceof RedirectResponse) return $category;

        $error = $this->validasiRequest([
            'name'        => "required|min_length[3]|max_length[100]|is_unique[categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
        ]);
        
        if ($error) return $error;

        if ($this->modelKategori->update($id, $this->getDataForm())) {
            return redirect()->to('/categories')->with('success', 'Kategori berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori');
    }

    /**
     * Hapus kategori
     */
    public function hapus($id): RedirectResponse
    {
        $category = $this->modelKategori->find($id);
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Kategori tidak ditemukan');
        }

        if ($this->modelKategori->bisaDihapus((int) $id) === false) {
            return redirect()->to('/categories')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh produk');
        }

        if ($this->modelKategori->delete($id)) {
            return redirect()->to('/categories')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/categories')->with('error', 'Gagal menghapus kategori');
    }
}
