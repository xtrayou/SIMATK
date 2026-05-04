<?php

namespace App\Models\Notifikasi;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'url',
        'product_id',
        'request_id',
        'stock_movement_id',
        'for_role',
        'is_read',
        'read_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ===== QUERY =====

    /**
     * Resolusi cakupan role untuk query notifikasi.
     */
    private function resolveRoleScopes(string $role): array
    {
        $role = strtolower(trim($role));

        if ($role === 'superadmin') {
            return ['superadmin', 'all'];
        }

        if (in_array($role, ['admin', 'staff', 'user'], true)) {
            return ['admin', 'all'];
        }

        return ['all'];
    }

    private function create(array $data): int|false
    {
        return $this->insert($data);
    }

    private function alreadyExists(string $type, string $refColumn, int $refId): bool
    {
        return (bool) $this->where('type', $type)
            ->where($refColumn, $refId)
            ->where('is_read', 0)
            ->first();
    }

    /**
     * Ambil notifikasi untuk role tertentu (belum dibaca)
     */
    public function getUnreadForRole(string $role, int $limit = 10): array
    {
        $roleScopes = $this->resolveRoleScopes($role);

        return $this->whereIn('for_role', $roleScopes)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Hitung notifikasi belum dibaca untuk role
     */
    public function countUnreadForRole(string $role): int
    {
        $roleScopes = $this->resolveRoleScopes($role);

        return $this->whereIn('for_role', $roleScopes)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Ambil semua notifikasi untuk role (paginated)
     */
    public function getForRole(string $role, int $perPage = 20)
    {
        $roleScopes = $this->resolveRoleScopes($role);

        return $this->whereIn('for_role', $roleScopes)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

            // ===== ACTION =====

    /**
     * Tandai satu notifikasi sebagai dibaca
     */
    public function markAsRead(int $id, ?int $userId): bool
    {
        return $this->update($id, [
            'is_read' => 1,
            'read_by' => $userId ?: null,
        ]);
    }

    /**
     * Tandai semua notifikasi role sebagai dibaca
     */
    public function markAllAsRead(string $role, ?int $userId): int
    {
        $roleScopes = $this->resolveRoleScopes($role);
        $builder = $this->builder();

        return $builder->whereIn('for_role', $roleScopes)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_by' => $userId ?: null,
            ]);
    }

    /**
     * Hapus notifikasi lama (> 30 hari)
     */
    public function cleanOld(int $days = 30): int
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoff)->delete();
    }

    // ===== FACTORY =====

    /**
     * Notifikasi: stok rendah
     */
    public function createLowStockNotification(array $product): int|false
    {
        $productId   = $product['id'];
        if ($this->alreadyExists('low_stock', 'product_id', (int) $productId)) {
            return false;
        }

        $productName = $product['name'] ?? 'Barang';
        $currentStock = $product['current_stock'] ?? 0;
        $minStock     = $product['min_stock'] ?? 0;

        return $this->create([
            'type'           => 'low_stock',
            'title'          => 'Stok Rendah!',
            'message'        => "Stok {$productName} tinggal {$currentStock} unit (minimum: {$minStock}).",
            'icon'           => 'bi-exclamation-triangle-fill',
            'color'          => 'warning',
            'url'            => "/products/show/{$productId}",
            'product_id'     => $productId,
            'for_role'       => 'all',
        ]);
    }

    /**
     * Notifikasi: stok habis
     */
    public function createOutOfStockNotification(array $product): int|false
    {
        $productId   = $product['id'];
        if ($this->alreadyExists('out_of_stock', 'product_id', (int) $productId)) {
            return false;
        }

        $productName = $product['name'] ?? 'Barang';

        return $this->create([
            'type'           => 'out_of_stock',
            'title'          => 'Stok Habis!',
            'message'        => "Stok {$productName} telah habis (0 unit).",
            'icon'           => 'bi-x-circle-fill',
            'color'          => 'danger',
            'url'            => "/products/show/{$productId}",
            'product_id'     => $productId,
            'for_role'       => 'all',
        ]);
    }

    /**
     * Notifikasi: permintaan ATK baru
     */
    public function createNewRequestNotification(array $request): int|false
    {
        $requestId = $request['id'] ?? 0;

        return $this->create([
            'type'           => 'new_request',
            'title'          => 'Permintaan ATK Baru',
            'message'        => "Ada permintaan ATK baru (Unit {$request['borrower_unit']}).",
            'icon'           => 'bi-journal-arrow-down',
            'color'          => 'info',
            'url'            => "/requests/show/{$requestId}",
            'request_id'     => $requestId,
            'for_role'       => 'admin',
        ]);
    }

    /**
     * Notifikasi: permintaan disetujui
     */
    public function createRequestApprovedNotification(array $request): int|false
    {
        $requestId = $request['id'] ?? 0;

        return $this->create([
            'type'           => 'request_approved',
            'title'          => 'Permintaan Disetujui',
            'message'        => "Permintaan #{$requestId} telah disetujui.",
            'icon'           => 'bi-check-circle-fill',
            'color'          => 'success',
            'url'            => "/requests/show/{$requestId}",
            'request_id'     => $requestId,
            'for_role'       => 'all',
        ]);
    }

    /**
     * Notifikasi: permintaan dibatalkan
     */
    public function createRequestCancelledNotification(array $request): int|false
    {
        $requestId = $request['id'] ?? 0;

        return $this->create([
            'type'           => 'request_cancelled',
            'title'          => 'Permintaan Dibatalkan',
            'message'        => "Permintaan #{$requestId} telah dibatalkan.",
            'icon'           => 'bi-x-circle',
            'color'          => 'secondary',
            'url'            => "/requests/show/{$requestId}",
            'request_id'     => $requestId,
            'for_role'       => 'all',
        ]);
    }

    /**
     * Notifikasi: barang masuk
     */
    public function createStockInNotification(string $productName, int $quantity, string $reference): int|false
    {
        return $this->create([
            'type'           => 'stock_in',
            'title'          => 'Barang Masuk',
            'message'        => "{$productName}: +{$quantity} unit masuk (Ref: {$reference}).",
            'icon'           => 'bi-arrow-down-circle-fill',
            'color'          => 'success',
            'url'            => '/stock/history',
            'for_role'       => 'admin',
        ]);
    }

    /**
     * Notifikasi: barang keluar
     */
    public function createStockOutNotification(string $productName, int $quantity, string $reference): int|false
    {
        return $this->create([
            'type'           => 'stock_out',
            'title'          => 'Barang Keluar',
            'message'        => "{$productName}: -{$quantity} unit keluar (Ref: {$reference}).",
            'icon'           => 'bi-arrow-up-circle-fill',
            'color'          => 'danger',
            'url'            => '/stock/history',
            'for_role'       => 'admin',
        ]);
    }
}
