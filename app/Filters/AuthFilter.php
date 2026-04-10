<?php

namespace App\Filters;

use App\Models\HakAksesModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    protected HakAksesModel $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new HakAksesModel();
    }

    /**
     * Cek apakah user sudah login dan memiliki permission
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, redirect ke landing page (berisi modal login)
            return redirect()->to('/')->with('loginError', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        }

        // --- Cek Inaktivitas (15 Menit = 900 detik) ---
        $lastActivity = session()->get('last_activity');
        $currentTime  = time();
        $timeout      = 900;

        if ($lastActivity && ($currentTime - $lastActivity > $timeout)) {
            session()->destroy();
            return redirect()->to('/')->with('loginError', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 15 menit.');
        }

        // Update waktu aktivitas terakhir
        session()->set('last_activity', $currentTime);

        // Refresh permission dengan TTL pendek agar perubahan role/hak akses cepat terpropagasi.
        $permissions = session()->get('permissions');
        $lastFetch = (int) (session()->get('perm_last_fetch') ?? 0);
        $userId = (int) (session()->get('userId') ?? 0);

        if ((!is_array($permissions) || ($currentTime - $lastFetch > 60)) && $userId > 0) {
            $permissions = $this->permissionModel->getByUser($userId);
            session()->set('permissions', $permissions);
            session()->set('perm_last_fetch', $currentTime);
        }
        // ----------------------------------------------

        // Cek permission jika ada argument $arguments dari filter
        if (!empty($arguments)) {
            $userPermissions = is_array($permissions) ? $permissions : [];
            $hasPermission = false;

            foreach ($arguments as $permission) {
                if (in_array($permission, $userPermissions, true)) {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                if ($request instanceof IncomingRequest && $request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses untuk fitur ini.']);
                }
                return redirect()->to('/dashboard')->with('galat', 'Anda tidak memiliki akses untuk fitur ini.');
            }
        }
    }

    /**
     * Tidak ada aksi setelah request
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
