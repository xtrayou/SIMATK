<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing page
$routes->get('/', 'BerandaController::index');

// Auth
$routes->get('login', 'BerandaController::index');
$routes->post('login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login');
$routes->post('logout', 'AuthController::logout');
$routes->post('auth/logout', 'AuthController::logout');

// Dashboard
$routes->get('dashboard', 'DasborController::index', ['filter' => 'auth']);
$routes->get('api/dashboard/stats', 'Api\DasborController::getStats', ['filter' => 'auth']);

// Categories
$routes->group('categories', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'KategoriController::index');
    $routes->get('create', 'KategoriController::create');
    $routes->post('store', 'KategoriController::store');
    $routes->get('edit/(:num)', 'KategoriController::edit/$1');
    $routes->post('update/(:num)', 'KategoriController::update/$1');
    $routes->delete('delete/(:num)', 'KategoriController::delete/$1');
});

// Products
$routes->group('products', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'ProdukController::index');
    $routes->get('create', 'ProdukController::create');
    $routes->get('show/(:num)', 'ProdukController::show/$1');
    $routes->post('store', 'ProdukController::store');
    $routes->get('edit/(:num)', 'ProdukController::edit/$1');
    $routes->post('update/(:num)', 'ProdukController::update/$1');
    $routes->delete('delete/(:num)', 'ProdukController::delete/$1');
    $routes->post('generate-sku', 'ProdukController::generateSKU');

    $routes->get('export/excel', 'ProdukController::exportExcel');
    $routes->get('export/pdf', 'ProdukController::exportPdf');
    $routes->get('export/(:num)', 'ProdukController::exportSingle/$1');
});

// Stock management
$routes->get('stock', 'StokController::movements', ['filter' => 'auth']);

$routes->group('stock', ['filter' => 'auth'], static function ($routes) {
    $routes->get('movements', 'StokController::movements');
    $routes->get('in', 'StokController::stockIn');
    $routes->post('in/store', 'StokController::storeStockIn');
    $routes->get('out', 'StokController::stockOut');
    $routes->post('out/store', 'StokController::storeStockOut');
    $routes->get('history', 'StokController::history');
    $routes->get('history/export/(:alpha)', 'StokController::exportHistory/$1');
    $routes->get('adjustment', 'StokController::adjustment');
    $routes->post('adjustment/store', 'StokController::storeAdjustment');
    $routes->get('alerts', 'StokController::alerts');
    $routes->get('product/(:num)', 'StokController::getProductStock/$1');
});

// Reports
$routes->group('reports', ['filter' => 'auth'], static function ($routes) {
    $routes->get('stock', 'LaporanController::stock');
    $routes->get('movements', 'LaporanController::movements');
    $routes->get('export/stock', 'LaporanController::exportStock');
    $routes->get('export/movements', 'LaporanController::exportMovements');
    $routes->get('stock/export/(:alpha)', 'LaporanController::exportStock/$1');
    $routes->get('movements/export/(:alpha)', 'LaporanController::exportMovements/$1');
    $routes->get('valuation', 'LaporanController::valuation');
    $routes->get('analytics', 'LaporanController::analytics');
});

// Generic API routes
$routes->group('api', ['filter' => 'auth'], static function ($routes) {
    $routes->get('products/search', 'Api\ProdukController::search');
    $routes->get('categories/active', 'Api\KategoriController::getActive');
    $routes->get('product/(:num)/info', 'Api\StokController::getProductInfo/$1');
    $routes->get('alerts/count', 'Api\StokController::getAlertsCount');
    $routes->post('bulk/in', 'Api\StokController::bulkStockIn');
    $routes->post('bulk/out', 'Api\StokController::bulkStockOut');
});

// Product API routes
$routes->group('api/products', ['filter' => 'auth'], static function ($routes) {
    $routes->get('search', 'Api\ProdukController::search');
    $routes->get('stock-status/(:num)', 'Api\ProdukController::getStockStatus/$1');
    $routes->get('by-category/(:num)', 'Api\ProdukController::getByCategory/$1');
});

// Requests
$routes->group('requests', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'PermintaanController::index');
    $routes->get('create', 'PermintaanController::create');
    $routes->post('store', 'PermintaanController::store');
    $routes->get('show/(:num)', 'PermintaanController::show/$1');
    $routes->post('approve/(:num)', 'PermintaanController::approve/$1');
    $routes->post('distribute/(:num)', 'PermintaanController::distribute/$1');
    $routes->post('cancel/(:num)', 'PermintaanController::cancel/$1');
});

// Users (superadmin only)
$routes->group('users', ['filter' => 'role:superadmin'], static function ($routes) {
    $routes->get('/', 'PenggunaController::index');
    $routes->get('create', 'PenggunaController::create');
    $routes->post('store', 'PenggunaController::store');
    $routes->get('edit/(:num)', 'PenggunaController::edit/$1');
    $routes->post('update/(:num)', 'PenggunaController::update/$1');
    $routes->delete('delete/(:num)', 'PenggunaController::delete/$1');
});

// Settings (superadmin only)
$routes->get('settings', 'PengaturanController::index', ['filter' => 'role:superadmin']);
$routes->post('settings/update', 'PengaturanController::update', ['filter' => 'role:superadmin']);

// Notifications
$routes->group('notifications', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'NotifikasiController::index');
    $routes->post('read/(:num)', 'NotifikasiController::read/$1');
    $routes->post('mark-all-read', 'NotifikasiController::markAllRead');
    $routes->post('delete/(:num)', 'NotifikasiController::delete/$1');
    $routes->post('clean-old', 'NotifikasiController::cleanOld');
});

$routes->group('api/notifications', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Api\NotifikasiController::latest');
    $routes->get('count', 'Api\NotifikasiController::count');
});

// Public request pages
$routes->get('ask', 'PermintaanController::askForm');
$routes->post('ask/store', 'PermintaanController::askStore');
$routes->get('ask/success', 'PermintaanController::askSuccess');
$routes->get('track', 'PermintaanController::trackForm');
$routes->post('track-status', 'PermintaanController::trackStatus');

// Maintenance (superadmin only)
$routes->post('admin/fix-request-status', static function () {
    $db = \Config\Database::connect();
    $sql = "UPDATE requests SET status = 'requested' WHERE status IS NULL OR status = '' OR status = 'pending'";
    $db->query($sql);
    $affected = $db->affectedRows();

    $stats = $db->query('SELECT status, COUNT(*) as total FROM requests GROUP BY status')->getResultArray();

    $output = '<h2>Status Diperbaiki</h2>';
    $output .= "<p><strong>{$affected}</strong> data telah diperbaiki.</p>";
    $output .= '<h3>Statistik:</h3><ul>';
    foreach ($stats as $row) {
        $status = $row['status'] ?: '(kosong)';
        $output .= "<li><strong>{$status}</strong>: {$row['total']}</li>";
    }
    $output .= '</ul>';
    $output .= '<p><a href="' . base_url('requests') . '">Kembali ke Daftar Permintaan</a></p>';

    return $output;
}, ['filter' => 'role:superadmin']);

$routes->post('admin/fix-session', static function () {
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

    $output = '<h2>Session Diperbaiki</h2>';
    $output .= "<p>Session telah diperbarui dengan userId: <strong>{$user['id']}</strong></p>";
    $output .= "<p>User: <strong>{$user['name']}</strong> ({$user['username']})</p>";
    $output .= '<hr>';
    $output .= '<p><a href="' . base_url('dashboard') . '">Kembali ke Dashboard</a></p>';

    return $output;
}, ['filter' => 'role:superadmin']);
