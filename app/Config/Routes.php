<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Landing Page (publik - berisi modal login) ──────────────────────
$routes->get('/', 'BerandaController::index');

// ── Auth ─────────────────────────────────────────────────────────────
$routes->get('login', 'BerandaController::index');   // fallback: tampilkan landing page
$routes->post('login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login'); // aksi form modal di landing page
$routes->post('logout', 'AuthController::logout');
$routes->post('auth/logout', 'AuthController::logout');

// ── Dashboard ────────────────────────────────────────────────────────
$routes->get('dashboard', 'DasborController::index', ['filter' => 'auth']);
$routes->get('api/dashboard/stats', 'Api\DasborController::getStats', ['filter' => 'auth']);

// ── Kategori ─────────────────────────────────────────────────────────
$routes->group('categories', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KategoriController::index', ['filter' => 'auth:categories.view']);
    $routes->get('create', 'KategoriController::tambah', ['filter' => 'auth:categories.create']);
    $routes->post('store', 'KategoriController::simpan', ['filter' => 'auth:categories.create']);
    $routes->get('edit/(:num)', 'KategoriController::ubah/$1', ['filter' => 'auth:categories.edit']);
    $routes->post('update/(:num)', 'KategoriController::perbarui/$1', ['filter' => 'auth:categories.edit']);
    $routes->delete('delete/(:num)', 'KategoriController::hapus/$1', ['filter' => 'auth:categories.delete']);
});

// ── Kode Barang ──────────────────────────────────────────────────────
$routes->group('kode-barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KodeBarangController::index');
});

// ── Produk ───────────────────────────────────────────────────────────
$routes->group('products', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ProdukController::index', ['filter' => 'auth:products.view']);
    $routes->get('create', 'ProdukController::tambah', ['filter' => 'auth:products.create']);
    $routes->get('show/(:num)', 'ProdukController::detail/$1', ['filter' => 'auth:products.view']);
    $routes->post('save', 'ProdukController::simpan', ['filter' => 'auth:products.create']);
    $routes->get('edit/(:num)', 'ProdukController::ubah/$1', ['filter' => 'auth:products.edit']);
    $routes->match(['post', 'delete'], 'delete/(:num)', 'ProdukController::hapus/$1', ['filter' => 'auth:products.delete']);
    $routes->post('generate-sku', 'ProdukController::generateKodeProduk', ['filter' => 'auth:products.create']);

    // Ekspor produk
    $routes->get('export/excel', 'ProdukController::exportExcel', ['filter' => 'auth:products.export']);
    $routes->get('export/pdf', 'ProdukController::exportPDF', ['filter' => 'auth:products.export']);
    $routes->get('export/(:num)', 'ProdukController::exportSingle/$1', ['filter' => 'auth:products.export']);
});

// ── Manajemen Stok ───────────────────────────────────────────────────
$routes->get('stock', 'StokController::movements', ['filter' => 'auth:stock.history']);

$routes->group('stock', ['filter' => 'auth'], function ($routes) {
    $routes->get('movements', 'StokController::movements', ['filter' => 'auth:stock.history']);
    $routes->get('in', 'StokController::stockIn', ['filter' => 'auth:stock.in']);
    $routes->post('in/store', 'StokController::storeStockIn', ['filter' => 'auth:stock.in']);
    $routes->get('out', 'StokController::stockOut', ['filter' => 'auth:stock.out']);
    $routes->post('out/store', 'StokController::storeStockOut', ['filter' => 'auth:stock.out']);
    $routes->get('history', 'StokController::history', ['filter' => 'auth:stock.history']);
    $routes->get('history/export/(:alpha)', 'StokController::exportHistory/$1', ['filter' => 'auth:stock.history']);
    $routes->get('adjustment', 'StokController::adjustment', ['filter' => 'auth:stock.adjustment']);
    $routes->post('adjustment/store', 'StokController::storeAdjustment', ['filter' => 'auth:stock.adjustment']);
    $routes->get('alerts', 'StokController::alerts', ['filter' => 'auth:stock.alerts']);
    $routes->get('product/(:num)', 'StokController::getProductStock/$1', ['filter' => 'auth:stock.history']);
});

// ── Laporan ──────────────────────────────────────────────────────────
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
    $routes->get('stock', 'LaporanController::stock', ['filter' => 'auth:reports.view']);
    $routes->get('movements', 'LaporanController::movements', ['filter' => 'auth:reports.view']);
    $routes->get('export/stock', 'LaporanController::exportStock', ['filter' => 'auth:reports.export']);
    $routes->get('export/movements', 'LaporanController::exportMovements', ['filter' => 'auth:reports.export']);
    $routes->get('stock/export/(:alpha)', 'LaporanController::exportStock/$1', ['filter' => 'auth:reports.export']);
    $routes->get('movements/export/(:alpha)', 'LaporanController::exportMovements/$1', ['filter' => 'auth:reports.export']);
    $routes->get('valuation', 'LaporanController::valuation', ['filter' => 'auth:reports.view']);
    $routes->get('analytics', 'LaporanController::analytics', ['filter' => 'auth:reports.view']);
});

// ── API Routes (AJAX) ────────────────────────────────────────────────
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('products/search', 'Api\ProdukController::search');
    $routes->get('categories/active', 'Api\KategoriController::getActive');
    $routes->get('product/(:num)/info', 'Api\StokController::getProductInfo/$1');
    $routes->get('alerts/count', 'Api\StokController::getAlertsCount');
    $routes->get('kode-barang', 'Api\KodeBarangController::index');
    $routes->post('bulk/in', 'Api\StokController::bulkStockIn');
    $routes->post('bulk/out', 'Api\StokController::bulkStockOut');
});

// ── API Produk ───────────────────────────────────────────────────────
$routes->group('api/products', ['filter' => 'auth'], function ($routes) {
    $routes->get('search', 'Api\ProdukController::search');
    $routes->get('autofill', 'Api\ProdukController::autofill');
    $routes->get('stock-status/(:num)', 'Api\ProdukController::getStockStatus/$1');
    $routes->get('by-category/(:num)', 'Api\ProdukController::getByCategory/$1');
});

// ── Permintaan ATK ───────────────────────────────────────────────────
$routes->group('requests', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PermintaanController::index', ['filter' => 'auth:requests.view']);
    $routes->get('create', 'PermintaanController::tambah', ['filter' => 'auth:requests.create']);
    $routes->post('store', 'PermintaanController::simpan', ['filter' => 'auth:requests.create']);
    $routes->get('show/(:num)', 'PermintaanController::detail/$1', ['filter' => 'auth:requests.view']);
    $routes->post('approve/(:num)', 'PermintaanController::setujui/$1', ['filter' => 'auth:requests.approve']);
    $routes->post('distribute/(:num)', 'PermintaanController::distribusikan/$1', ['filter' => 'auth:requests.approve']);
    $routes->post('cancel/(:num)', 'PermintaanController::batalkan/$1', ['filter' => 'auth:requests.cancel']);
});

// ── Manajemen Pengguna (Permission Based) ───────────────────────────
$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PenggunaController::index', ['filter' => 'auth:users.view']);
    $routes->get('create', 'PenggunaController::tambah', ['filter' => 'auth:users.create']);
    $routes->post('store', 'PenggunaController::simpan', ['filter' => 'auth:users.create']);
    $routes->get('edit/(:num)', 'PenggunaController::ubah/$1', ['filter' => 'auth:users.edit']);
    $routes->post('update/(:num)', 'PenggunaController::perbarui/$1', ['filter' => 'auth:users.edit']);
    $routes->delete('delete/(:num)', 'PenggunaController::hapus/$1', ['filter' => 'auth:users.delete']);
});

// ── Manajemen Hak Akses Role (Permission Based) ─────────────────────
$routes->group('permissions', ['filter' => 'auth:permissions.manage'], function ($routes) {
    $routes->get('/', 'HakAksesController::index', ['filter' => 'auth:permissions.manage']);
    $routes->post('update', 'HakAksesController::update', ['filter' => 'auth:permissions.manage']);
});

// ── Pengaturan (Permission Based) ────────────────────────────────────
$routes->get('settings', 'PengaturanController::index', ['filter' => 'auth:settings.view']);
$routes->post('settings/update', 'PengaturanController::update', ['filter' => 'auth:settings.update']);

// ── Notifikasi ───────────────────────────────────────────────────────
$routes->group('notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'NotifikasiController::index', ['filter' => 'auth:notifications.view']);
    $routes->post('read/(:num)', 'NotifikasiController::read/$1', ['filter' => 'auth:notifications.view']);
    $routes->post('mark-all-read', 'NotifikasiController::markAllRead', ['filter' => 'auth:notifications.view']);
    $routes->post('delete/(:num)', 'NotifikasiController::delete/$1', ['filter' => 'auth:notifications.view']);
    $routes->post('clean-old', 'NotifikasiController::cleanOld', ['filter' => 'auth:notifications.view']);
});

// ── API Notifikasi ───────────────────────────────────────────────────
$routes->group('api/notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Api\NotifikasiController::latest', ['filter' => 'auth:notifications.view']);
    $routes->get('count', 'Api\NotifikasiController::count', ['filter' => 'auth:notifications.view']);
});

// ── Halaman Publik - Form Permintaan Barang ──────────────────────────
$routes->get('ask', 'PermintaanController::askForm');
$routes->post('ask/store', 'PermintaanController::askStore');
$routes->get('ask/success', 'PermintaanController::askSuccess');

// ── Halaman Publik - Lacak Status Permintaan ─────────────────────────
$routes->get('track', 'PermintaanController::trackForm');
$routes->post('track-status', 'PermintaanController::lacakStatus');

// ── Maintenance Routes (Permission Based) ─────────────────────────────
$routes->match(['post'], 'admin/fix-request-status', function () {
    $db = \Config\Database::connect();
    $sql = "UPDATE requests SET status = 'requested' WHERE status IS NULL OR status = '' OR status = 'pending'";
    $db->query($sql);
    $affected = $db->affectedRows();

    $stats = $db->query("SELECT status, COUNT(*) as total FROM requests GROUP BY status")->getResultArray();

    $output = "<h2>✅ Status Diperbaiki</h2>";
    $output .= "<p><strong>{$affected}</strong> data telah diperbaiki.</p>";
    $output .= "<h3>Statistik:</h3><ul>";
    foreach ($stats as $row) {
        $status = $row['status'] ?: '(kosong)';
        $output .= "<li><strong>{$status}</strong>: {$row['total']}</li>";
    }
    $output .= "</ul>";
    $output .= '<p><a href="' . base_url('requests') . '">← Kembali ke Daftar Permintaan</a></p>';

    return $output;
}, ['filter' => 'auth:permissions.manage']);

// Fix Session - Regenerate userId in session
$routes->match(['post'], 'admin/fix-session', function () {
    $username = session()->get('username');

    if (!$username) {
        return redirect()->to('/login')->with('error', 'Session tidak valid. Silakan login.');
    }

    $db = \Config\Database::connect();
    $user = $db->table('users')->where('username', $username)->get()->getRowArray();

    if (!$user) {
        session()->destroy();
        return redirect()->to('/login')->with('error', 'User tidak ditemukan. Silakan login ulang.');
    }

    session()->set('userId', $user['id']);

    $output = "<h2>✅ Session Diperbaiki</h2>";
    $output .= "<p>Session telah diperbarui dengan userId: <strong>{$user['id']}</strong></p>";
    $output .= "<p>User: <strong>{$user['name']}</strong> ({$user['username']})</p>";
    $output .= '<hr>';
    $output .= '<p><a href="' . base_url('dashboard') . '">← Kembali ke Dashboard</a></p>';

    return $output;
}, ['filter' => 'auth:permissions.manage']);
