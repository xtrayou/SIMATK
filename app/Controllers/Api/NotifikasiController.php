<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
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
     * GET /api/notifications — notifikasi terbaru (untuk dropdown navbar)
     */
    public function latest()
    {
        $this->ensureNotificationsViewPermission();

        $role  = session()->get('role') ?? 'admin';
        $limit = (int) ($this->request->getGet('limit') ?? 5);

        $notifications = $this->modelNotifikasi->getUnreadForRole($role, $limit);
        $unreadCount   = $this->modelNotifikasi->countUnreadForRole($role);

        return $this->jsonResponse([
            'status'       => true,
            'unread_count' => $unreadCount,
            'data'         => $notifications,
        ]);
    }

    /**
     * GET /api/notifications/count — jumlah belum dibaca
     */
    public function count()
    {
        $this->ensureNotificationsViewPermission();

        $role = session()->get('role') ?? 'admin';
        $count = $this->modelNotifikasi->countUnreadForRole($role);

        return $this->jsonResponse([
            'status' => true,
            'count'  => $count,
        ]);
    }
}
