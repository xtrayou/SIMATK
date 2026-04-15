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
    $routes->get('/', 'KategoriController::index', ['filter' => 'auth']);
    $routes->get('create', 'KategoriController::tambah', ['filter' => 'auth']);
    $routes->post('store', 'KategoriController::simpan', ['filter' => 'auth']);
    $routes->get('edit/(:num)', 'KategoriController::ubah/$1', ['filter' => 'auth']);
    $routes->post('update/(:num)', 'KategoriController::perbarui/$1', ['filter' => 'auth']);
    $routes->delete('delete/(:num)', 'KategoriController::hapus/$1', ['filter' => 'auth']);
});

// ── Kode Barang ──────────────────────────────────────────────────────
$routes->group('kode-barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KodeBarangController::index');
});

// ── Barang ───────────────────────────────────────────────────────────
$routes->group('products', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BarangController::index', ['filter' => 'auth']);
    $routes->get('create', 'BarangController::tambah', ['filter' => 'auth']);
    $routes->get('show/(:num)', 'BarangController::detail/$1', ['filter' => 'auth']);
    $routes->post('save', 'BarangController::simpan', ['filter' => 'auth']);
    $routes->get('edit/(:num)', 'BarangController::ubah/$1', ['filter' => 'auth']);
    $routes->match(['post', 'delete'], 'delete/(:num)', 'BarangController::hapus/$1', ['filter' => 'auth']);
    $routes->post('generate-sku', 'BarangController::generateKodeBarang', ['filter' => 'auth']);

    // Ekspor barang
    $routes->get('export/excel', 'BarangController::exportExcel', ['filter' => 'auth']);
    $routes->get('export/pdf', 'BarangController::exportPDF', ['filter' => 'auth']);
    $routes->get('export/(:num)', 'BarangController::exportSingle/$1', ['filter' => 'auth']);
});

// ── Manajemen Stok ───────────────────────────────────────────────────
$routes->get('stock', 'StokController::movements', ['filter' => 'auth']);

$routes->group('stock', ['filter' => 'auth'], function ($routes) {
    $routes->get('movements', 'StokController::movements', ['filter' => 'auth']);
    $routes->get('in', 'StokController::stockIn', ['filter' => 'auth']);
    $routes->post('in/store', 'StokController::storeStockIn', ['filter' => 'auth']);
    $routes->get('out', 'StokController::stockOut', ['filter' => 'auth']);
    $routes->post('out/store', 'StokController::storeStockOut', ['filter' => 'auth']);
    $routes->get('history', 'StokController::history', ['filter' => 'auth']);
    $routes->get('history/export/(:alpha)', 'StokController::exportHistory/$1', ['filter' => 'auth']);
    $routes->get('adjustment', 'StokController::adjustment', ['filter' => 'auth']);
    $routes->post('adjustment/store', 'StokController::storeAdjustment', ['filter' => 'auth']);
    $routes->get('alerts', 'StokController::alerts', ['filter' => 'auth']);
    $routes->get('product/(:num)', 'StokController::getProductStock/$1', ['filter' => 'auth']);
});

// ── Laporan ──────────────────────────────────────────────────────────
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
    $routes->get('stock', 'LaporanController::stock', ['filter' => 'auth']);
    $routes->get('movements', 'LaporanController::movements', ['filter' => 'auth']);
    $routes->get('export/stock', 'LaporanController::exportStock', ['filter' => 'auth']);
    $routes->get('export/movements', 'LaporanController::exportMovements', ['filter' => 'auth']);
    $routes->get('stock/export/(:alpha)', 'LaporanController::exportStock/$1', ['filter' => 'auth']);
    $routes->get('movements/export/(:alpha)', 'LaporanController::exportMovements/$1', ['filter' => 'auth']);
    $routes->get('valuation', 'LaporanController::valuation', ['filter' => 'auth']);
    $routes->get('analytics', 'LaporanController::analytics', ['filter' => 'auth']);
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
    $routes->get('/', 'PermintaanController::index', ['filter' => 'auth']);
    $routes->get('create', 'PermintaanController::tambah', ['filter' => 'auth']);
    $routes->post('store', 'PermintaanController::simpan', ['filter' => 'auth']);
    $routes->get('show/(:num)', 'PermintaanController::detail/$1', ['filter' => 'auth']);
    $routes->post('approve/(:num)', 'PermintaanController::setujui/$1', ['filter' => 'auth']);
    $routes->post('distribute/(:num)', 'PermintaanController::distribusikan/$1', ['filter' => 'auth']);
    $routes->post('cancel/(:num)', 'PermintaanController::batalkan/$1', ['filter' => 'auth']);
});

// ── Manajemen Pengguna dan Hak Akses (Role Based) ───────────────────
$routes->group('users', ['filter' => 'role:superadmin'], function ($routes) {
    $routes->get('/', 'PenggunaController::index', ['filter' => 'role:superadmin']);
    $routes->get('create', 'PenggunaController::tambah', ['filter' => 'role:superadmin']);
    $routes->post('store', 'PenggunaController::simpan', ['filter' => 'role:superadmin']);
    $routes->get('edit/(:num)', 'PenggunaController::ubah/$1', ['filter' => 'role:superadmin']);
    $routes->post('update/(:num)', 'PenggunaController::perbarui/$1', ['filter' => 'role:superadmin']);
    $routes->delete('delete/(:num)', 'PenggunaController::hapus/$1', ['filter' => 'role:superadmin']);
});

// ── Pengaturan ───────────────────────────────────────────────────────
$routes->get('settings', 'PengaturanController::index', ['filter' => 'auth']);
$routes->post('settings/update', 'PengaturanController::update', ['filter' => 'auth']);

// ── Notifikasi ───────────────────────────────────────────────────────
$routes->group('notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'NotifikasiController::index', ['filter' => 'auth']);
    $routes->post('read/(:num)', 'NotifikasiController::read/$1', ['filter' => 'auth']);
    $routes->post('mark-all-read', 'NotifikasiController::markAllRead', ['filter' => 'auth']);
    $routes->post('delete/(:num)', 'NotifikasiController::delete/$1', ['filter' => 'auth']);
    $routes->post('clean-old', 'NotifikasiController::cleanOld', ['filter' => 'auth']);
});

// ── API Notifikasi ───────────────────────────────────────────────────
$routes->group('api/notifications', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Api\NotifikasiController::latest', ['filter' => 'auth']);
    $routes->get('count', 'Api\NotifikasiController::count', ['filter' => 'auth']);
});

// ── Halaman Publik - Form Permintaan Barang ──────────────────────────
$routes->get('ask', 'PermintaanController::askForm');
$routes->post('ask/store', 'PermintaanController::askStore');
$routes->get('ask/success', 'PermintaanController::askSuccess');

// ── Halaman Publik - Lacak Status Permintaan ─────────────────────────
$routes->get('track', 'PermintaanController::trackForm');
$routes->post('track-status', 'PermintaanController::lacakStatus');

// ── Maintenance Routes (Role Based) ───────────────────────────────────
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
}, ['filter' => 'role:superadmin']);

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
}, ['filter' => 'role:superadmin']);
