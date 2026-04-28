<?php

namespace App\Controllers\MasterData;

use App\Controllers\BaseController;
use App\Models\MasterData\KategoriModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * KategoriController - Controller untuk mengelola kategori barang
 *
 * Relasi:
 * - Kategori memiliki banyak Barang
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
            'name'        => trim($this->request->getPost('name') ?? ''),
            'description' => trim($this->request->getPost('description') ?? ''),
            'is_active'   => (bool) $this->request->getPost('is_active'),
        ];
    }

    /**
     * Validasi request dan kembalikan redirect jika gagal
     */
    private function validasiRequest(array $rules): ?RedirectResponse
    {
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        return null;
    }

    /**
     * Cari kategori berdasarkan ID atau redirect jika tidak ditemukan
     */
    private function findOrRedirect(int $id): array|RedirectResponse
    {
        $kategori = $this->modelKategori->find($id);

        if ($kategori) {
            return $kategori;
        }

        return redirect()->to('/categories')
            ->with('error', 'Kategori tidak ditemukan');
    }

    /**
     * Siapkan data untuk view form create/edit
     */
    private function getFormViewData(array $kategori, bool $isEdit): array
    {
        $oldInput = session()->getFlashdata('_ci_old_input') ?? [];
        $hasOldInput = !empty($oldInput);

        $formName = $hasOldInput
            ? trim((string) ($oldInput['name'] ?? ''))
            : trim((string) ($kategori['name'] ?? ''));
        $formDescription = $hasOldInput
            ? trim((string) ($oldInput['description'] ?? ''))
            : trim((string) ($kategori['description'] ?? ''));

        $formIsActive = $hasOldInput
            ? array_key_exists('is_active', $oldInput)
            : (bool) ($kategori['is_active'] ?? true);

        $previewName = $formName !== '' ? $formName : 'Nama Kategori';
        $previewDescription = $formDescription !== '' ? $formDescription : null;
        $previewStatusClass = $formIsActive ? 'bg-success' : 'bg-secondary';
        $previewStatusIcon = $formIsActive ? 'bi-check-circle' : 'bi-x-circle';
        $previewStatusText = $formIsActive ? 'Aktif' : 'Nonaktif';

        return [
            'kategori' => $kategori,
            'isEdit' => $isEdit,
            'judulForm' => $isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru',
            'actionUrl' => $isEdit
                ? '/categories/update/' . (int) ($kategori['id'] ?? 0)
                : '/categories/store',
            'methodSpoof' => $isEdit ? 'PUT' : null,
            'submitLabel' => $isEdit ? 'Perbarui Kategori' : 'Simpan Kategori',
            'previewModeLabel' => $isEdit ? 'Sedang diedit' : 'Baru dibuat',
            'formName' => $formName,
            'formDescription' => $formDescription,
            'formIsActive' => $formIsActive,
            'previewName' => $previewName,
            'previewDescription' => $previewDescription,
            'previewStatusClass' => $previewStatusClass,
            'previewStatusIcon' => $previewStatusIcon,
            'previewStatusText' => $previewStatusText,
        ];
    }

    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $this->setPageData('Kategori', 'Manajemen Kategori Barang');

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
        $categories = $this->modelKategori->getKategoriDenganJumlahBarang(
            $keyword,
            $filterStatus,
            $orderBy,
            $orderDir,
            $perPage,
            $offset
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
        $this->setPageData('Tambah Kategori', 'Buat kategori barang baru');

        $kategori = ['name' => '', 'description' => '', 'is_active' => true];
        return $this->render('kategori/form', array_merge(
            $this->getFormViewData($kategori, false),
            ['validation' => service('validation')]
        ));
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

        if ($error) {
            return $error;
        }

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
        $category = $this->findOrRedirect((int) $id);
        if ($category instanceof RedirectResponse) {
            return $category;
        }

        $this->setPageData('Edit Kategori', 'Edit Kategori: ' . $category['name']);

        return $this->render('kategori/form', array_merge(
            $this->getFormViewData($category, true),
            ['validation' => service('validation')]
        ));
    }

    /**
     * Perbarui data kategori
     */
    public function perbarui($id)
    {
        $category = $this->findOrRedirect((int) $id);
        if ($category instanceof RedirectResponse) {
            return $category;
        }

        $error = $this->validasiRequest([
            'name'        => "required|min_length[3]|max_length[100]|is_unique[categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
        ]);

        if ($error) {
            return $error;
        }

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
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh barang');
        }

        if ($this->modelKategori->delete($id)) {
            return redirect()->to('/categories')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/categories')->with('error', 'Gagal menghapus kategori');
    }
}
