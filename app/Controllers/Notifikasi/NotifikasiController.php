<?php

namespace App\Controllers\Notifikasi;

use App\Controllers\BaseController;
use App\Models\Notifikasi\NotifikasiModel;

class NotifikasiController extends BaseController
{
    protected NotifikasiModel $modelNotifikasi;

    public function __construct()
    {
        $this->modelNotifikasi = new NotifikasiModel();
    }

    private function getRole(): string
    {
        return session()->get('role') ?? 'admin';
    }

    private function getRoleScopes(string $role): array
    {
        return $role === 'superadmin'
            ? ['superadmin', 'admin', 'all']
            : [$role, 'all'];
    }

    private function findNotification(int $id, array $roleScopes): ?array
    {
        return $this->modelNotifikasi
            ->where('id', $id)
            ->whereIn('for_role', $roleScopes)
            ->first();
    }

    /**
     * Halaman daftar semua notifikasi
     */
    public function index()
    {
        $this->setPageData('Notifikasi', 'Daftar semua notifikasi sistem');

        $role = $this->getRole();

        $notifications = $this->modelNotifikasi->getForRole($role, 20);
        $pager = $this->modelNotifikasi->pager;

        $data = [
            'notifications' => $notifications,
            'pager'         => $pager,
            'unreadCount'   => $this->modelNotifikasi->countUnreadForRole($role),
        ];

        return $this->render('notifications/index', $data);
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan redirect ke URL tujuan
     */
    public function read($id)
    {
        $notificationId = (int) $id;
        $role = $this->getRole();
        $notification = $this->findNotification($notificationId, $this->getRoleScopes($role));

        if (!$notification) {
            return redirect()->to('/notifications')
                ->with('error', 'Notifikasi tidak ditemukan atau tidak dapat diakses.');
        }

        $this->modelNotifikasi->markAsRead($notificationId, session()->get('userId'));

        return redirect()->to($notification['url'] ?? '/notifications');
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllRead()
    {
        $role   = $this->getRole();
        $userId = session()->get('userId');

        $this->modelNotifikasi->markAllAsRead($role, $userId);

        if ($this->isAjax()) {
            return $this->jsonResponse(['status' => true, 'message' => 'Semua notifikasi telah dibaca.']);
        }

        return redirect()->to('/notifications')->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Hapus satu notifikasi
     */
    public function delete($id)
    {
        $notificationId = (int) $id;
        $role = $this->getRole();
        $notification = $this->findNotification($notificationId, $this->getRoleScopes($role));

        if (!$notification) {
            return $this->jsonResponse(['status' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        $this->modelNotifikasi->delete($notificationId);

        return $this->isAjax()
            ? $this->jsonResponse(['status' => true, 'message' => 'Notifikasi dihapus.'])
            : redirect()->to('/notifications')->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Hapus notifikasi lama (admin only)
     */
    public function cleanOld()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'superadmin'], true)) {
            return $this->jsonResponse(['status' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $deleted = $this->modelNotifikasi->cleanOld(30);

        return $this->jsonResponse([
            'status'  => true,
            'message' => "Berhasil menghapus {$deleted} notifikasi lama.",
        ]);
    }
}
