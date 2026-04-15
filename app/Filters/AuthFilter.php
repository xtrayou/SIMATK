<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Cek apakah user sudah login
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
    }

    /**
     * Tidak ada aksi setelah request
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
