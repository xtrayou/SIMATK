<?php

namespace App\Controllers;

use App\Exceptions\PageForbiddenException;
use App\Models\NotifikasiModel;

class NotifikasiController extends BaseController
{
    protected NotifikasiModel $modelNotifikasi;

    private function ensureNotificationsViewPermission(): void
    {
        $permissions = session()->get('permissions') ?? [];
        if (!is_array($permissions) || !in_array('notifications.view', $permissions, true)) {
            throw PageForbiddenException::forPageForbidden();
        }
    }

    public function __construct()
    {
        $this->modelNotifikasi = new NotifikasiModel();
    }

    /**
     * Halaman daftar semua notifikasi
     */
    public function index()
    {
        $this->ensureNotificationsViewPermission();

        $this->setPageData('Notifikasi', 'Daftar semua notifikasi sistem');

        $role = session()->get('role') ?? 'admin';

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
        $this->ensureNotificationsViewPermission();

        $role = session()->get('role') ?? 'admin';
        $roleScopes = $role === 'superadmin' ? ['superadmin', 'admin', 'all'] : [$role, 'all'];
        $notification = $this->modelNotifikasi
            ->where('id', (int) $id)
            ->whereIn('for_role', $roleScopes)
            ->first();

        if (!$notification) {
            return redirect()->to('/notifications')->with('error', 'Notifikasi tidak ditemukan atau tidak dapat diakses.');
        }

        $userId = session()->get('userId') ?: null;
        $this->modelNotifikasi->markAsRead($id, $userId);

        // Redirect ke URL tujuan jika tersedia
        if (!empty($notification['url'])) {
            return redirect()->to($notification['url']);
        }

        return redirect()->to('/notifications');
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllRead()
    {
        $this->ensureNotificationsViewPermission();

        $role   = session()->get('role') ?? 'admin';
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
        $this->ensureNotificationsViewPermission();

        $role = session()->get('role') ?? 'admin';
        $roleScopes = $role === 'superadmin' ? ['superadmin', 'admin', 'all'] : [$role, 'all'];
        $notification = $this->modelNotifikasi
            ->where('id', (int) $id)
            ->whereIn('for_role', $roleScopes)
            ->first();

        if (!$notification) {
            return $this->jsonResponse(['status' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        $this->modelNotifikasi->delete($id);

        if ($this->isAjax()) {
            return $this->jsonResponse(['status' => true, 'message' => 'Notifikasi dihapus.']);
        }

        return redirect()->to('/notifications')->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Hapus notifikasi lama (admin only)
     */
    public function cleanOld()
    {
        $this->ensureNotificationsViewPermission();

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
