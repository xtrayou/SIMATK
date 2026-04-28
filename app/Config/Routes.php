<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Landing Page (publik - berisi modal login) ──────────────────────
$routes->get('/', 'Beranda\BerandaController::index');

// ── Auth ─────────────────────────────────────────────────────────────
$routes->get('login', 'Beranda\BerandaController::index');   // fallback: tampilkan landing page
$routes->post('login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login'); // aksi form modal di landing page
$routes->post('logout', 'AuthController::logout');
$routes->post('auth/logout', 'AuthController::logout');

// ── Dashboard ────────────────────────────────────────────────────────
$routes->get('dashboard', 'Dashboard\DasborController::index', ['filter' => 'auth']);

// ── Kategori ─────────────────────────────────────────────────────────
$routes->group('categories', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterData\KategoriController::index', ['filter' => 'auth']);
    $routes->get('create', 'MasterData\KategoriController::tambah', ['filter' => 'auth']);
    $routes->post('store', 'MasterData\KategoriController::simpan', ['filter' => 'auth']);
    $routes->get('edit/(:num)', 'MasterData\KategoriController::ubah/$1', ['filter' => 'auth']);
    $routes->post('update/(:num)', 'MasterData\KategoriController::perbarui/$1', ['filter' => 'auth']);
    $routes->delete('delete/(:num)', 'MasterData\KategoriController::hapus/$1', ['filter' => 'auth']);
});

// ── Kode Barang ──────────────────────────────────────────────────────
$routes->group('kode-barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterData\KodeBarangController::index');
});

// ── Barang ───────────────────────────────────────────────────────────
$routes->group('products', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MasterData\BarangController::index', ['filter' => 'auth']);
    $routes->get('create', 'MasterData\BarangController::tambah', ['filter' => 'auth']);
    $routes->get('show/(:num)', 'MasterData\BarangController::detail/$1', ['filter' => 'auth']);
    $routes->post('save', 'MasterData\BarangController::simpan', ['filter' => 'auth']);
    $routes->get('edit/(:num)', 'MasterData\BarangController::ubah/$1', ['filter' => 'auth']);
    $routes->match(['post', 'delete'], 'delete/(:num)', 'MasterData\BarangController::hapus/$1', ['filter' => 'auth']);
    $routes->post('generate-sku', 'MasterData\BarangController::generateKodeBarang', ['filter' => 'auth']);
    $routes->get('export/excel', 'MasterData\BarangController::exportExcel', ['filter' => 'auth']);
    $routes->get('export/pdf', 'MasterData\BarangController::exportPdf', ['filter' => 'auth']);
    $routes->get('export/single/(:num)', 'MasterData\BarangController::exportSingle/$1', ['filter' => 'auth']);
});

// ── Manajemen Stok ───────────────────────────────────────────────────
$routes->get('stock', 'Stok\StokController::movements', ['filter' => 'auth']);

$routes->group('stock', ['filter' => 'auth'], function ($routes) {
    $routes->get('movements', 'Stok\StokController::movements', ['filter' => 'auth']);
    $routes->get('in', 'Stok\StokController::stockIn', ['filter' => 'auth']);
    $routes->post('in/store', 'Stok\StokController::storeStockIn', ['filter' => 'auth']);
    $routes->get('out', 'Stok\StokController::stockOut', ['filter' => 'auth']);
    $routes->post('out/store', 'Stok\StokController::storeStockOut', ['filter' => 'auth']);
    $routes->get('history', 'Stok\StokController::history', ['filter' => 'auth']);
    $routes->get('history/export/(:alpha)', 'Stok\StokController::exportHistory/$1', ['filter' => 'auth']);
    $routes->get('adjustment', 'Stok\StokController::adjustment', ['filter' => 'auth']);
    $routes->post('adjustment/store', 'Stok\StokController::storeAdjustment', ['filter' => 'auth']);

    $routes->get('alerts', 'Stok\StokController::alerts', ['filter' => 'auth']);
    $routes->get('product/(:num)', 'Stok\StokController::getProductStock/$1', ['filter' => 'auth']);
});

// ── Laporan ──────────────────────────────────────────────────────────
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
    $routes->get('stock', 'Laporan\LaporanController::stock', ['filter' => 'auth']);
    $routes->get('movements', 'Laporan\LaporanController::movements', ['filter' => 'auth']);
    $routes->get('export/stock', 'Laporan\LaporanController::exportStock', ['filter' => 'auth']);
    $routes->get('export/movements', 'Laporan\LaporanController::exportMovements', ['filter' => 'auth']);
    $routes->get('stock/export/(:alpha)', 'Laporan\LaporanController::exportStock/$1', ['filter' => 'auth']);
    $routes->get('movements/export/(:alpha)', 'Laporan\LaporanController::exportMovements/$1', ['filter' => 'auth']);
    $routes->get('valuation', 'Laporan\LaporanController::valuation', ['filter' => 'auth']);
    $routes->get('analytics', 'Laporan\LaporanController::analytics', ['filter' => 'auth']);
});

// ── API Routes (AJAX) ────────────────────────────────────────────────
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('products/search', 'Api\BarangController::search');
    $routes->get('categories/active', 'Api\KategoriController::getActive');
    $routes->get('product/(:num)/info', 'Api\StokController::getProductInfo/$1');
    $routes->get('alerts/count', 'Api\StokController::getAlertsCount');
    $routes->get('kode-barang', 'Api\KodeBarangController::index');
    $routes->post('bulk/in', 'Api\StokController::bulkStockIn');
    $routes->post('bulk/out', 'Api\StokController::bulkStockOut');
});

// ── API Barang ───────────────────────────────────────────────────────
$routes->group('api/products', ['filter' => 'auth'], function ($routes) {
    $routes->get('search', 'Api\BarangController::search');
    $routes->get('autofill', 'Api\BarangController::autofill');
    $routes->get('stock-status/(:num)', 'Api\BarangController::getStockStatus/$1');
    $routes->get('by-category/(:num)', 'Api\BarangController::getByCategory/$1');
});

// ── Permintaan ATK ───────────────────────────────────────────────────
$routes->group('requests', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Permintaan\PermintaanController::index', ['filter' => 'auth']);
    $routes->get('create', 'Permintaan\PermintaanController::tambah', ['filter' => 'auth']);
    $routes->post('store', 'Permintaan\PermintaanController::simpan', ['filter' => 'auth']);
    $routes->get('show/(:num)', 'Permintaan\PermintaanController::detail/$1', ['filter' => 'auth']);
    $routes->post('approve/(:num)', 'Permintaan\PermintaanController::setujui/$1', ['filter' => 'auth']);
    $routes->post('distribute/(:num)', 'Permintaan\PermintaanController::distribusikan/$1', ['filter' => 'auth']);
    $routes->post('cancel/(:num)', 'Permintaan\PermintaanController::batalkan/$1', ['filter' => 'auth']);
});

// ── Manajemen Pengguna dan Hak Akses (Role Based) ───────────────────
$routes->group('users', ['filter' => 'role:superadmin'], function ($routes) {
    $routes->get('/', 'Pengguna\PenggunaController::index', ['filter' => 'role:superadmin']);
    $routes->get('create', 'Pengguna\PenggunaController::tambah', ['filter' => 'role:superadmin']);
    $routes->post('store', 'Pengguna\PenggunaController::simpan', ['filter' => 'role:superadmin']);
    $routes->get('edit/(:num)', 'Pengguna\PenggunaController::ubah/$1', ['filter' => 'role:superadmin']);
    $routes->post('update/(:num)', 'Pengguna\PenggunaController::perbarui/$1', ['filter' => 'role:superadmin']);
    $routes->delete('delete/(:num)', 'Pengguna\PenggunaController::hapus/$1', ['filter' => 'role:superadmin']);
});

// ── Pengaturan ───────────────────────────────────────────────────────
$routes->get('settings', 'Pengaturan\PengaturanController::index', ['filter' => 'auth']);
$routes->post('settings/update', 'Pengaturan\PengaturanController::update', ['filter' => 'auth']);
$routes->post('settings/update-appearance', 'Pengaturan\PengaturanController::updateAppearance', ['filter' => 'role:superadmin']);


// ── Notifikasi ───────────────────────────────────────────────────────
$routes->group('notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Notifikasi\NotifikasiController::index', ['filter' => 'auth']);
    $routes->post('read/(:num)', 'Notifikasi\NotifikasiController::read/$1', ['filter' => 'auth']);
    $routes->post('mark-all-read', 'Notifikasi\NotifikasiController::markAllRead', ['filter' => 'auth']);
    $routes->post('delete/(:num)', 'Notifikasi\NotifikasiController::delete/$1', ['filter' => 'auth']);
    $routes->post('clean-old', 'Notifikasi\NotifikasiController::cleanOld', ['filter' => 'auth']);
});

// ── API Notifikasi ───────────────────────────────────────────────────
$routes->group('api/notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Api\NotifikasiController::latest', ['filter' => 'auth']);
    $routes->get('count', 'Api\NotifikasiController::count', ['filter' => 'auth']);
});

// ── Halaman Publik - Form Permintaan Barang ──────────────────────────
$routes->get('ask', 'Permintaan\PermintaanController::askForm');
$routes->post('ask/store', 'Permintaan\PermintaanController::askStore');
$routes->get('ask/success', 'Permintaan\PermintaanController::askSuccess');

// ── Halaman Publik - Lacak Status Permintaan ─────────────────────────
$routes->get('track', 'Permintaan\PermintaanController::trackForm');
$routes->post('track-status', 'Permintaan\PermintaanController::lacakStatus');
