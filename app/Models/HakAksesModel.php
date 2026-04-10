<?php

namespace App\Models;

use CodeIgniter\Model;

class HakAksesModel extends Model
{
    protected $table            = 'permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'group', 'description'];

    private function normalizeRole(string $role): string
    {
        $role = strtolower(trim($role));

        if (in_array($role, ['staff', 'user'], true)) {
            return 'admin';
        }

        return in_array($role, ['superadmin', 'admin'], true) ? $role : 'admin';
    }

    /**
     * Daftar role yang dikelola di modul hak akses.
     */
    public function getManageableRoles(): array
    {
        return ['superadmin', 'admin'];
    }

    /**
     * Ambil semua permission berdasarkan role
     */
    public function getPermissionsByRole(string $role): array
    {
        $role = $this->normalizeRole($role);

        return $this->select('permissions.*')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->where('role_permissions.role', $role)
            ->findAll();
    }

    /**
     * Ambil nama-nama permission untuk role tertentu (flat array)
     */
    public function getPermissionNamesByRole(string $role): array
    {
        $perms = $this->getPermissionsByRole($role);
        return array_column($perms, 'name');
    }

    /**
     * Ambil nama permission berdasarkan user yang sedang login.
     */
    public function getByUser(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->select('role')
            ->where('id', $userId)
            ->get()
            ->getRowArray();

        if (!$user || empty($user['role'])) {
            return [];
        }

        return $this->getPermissionNamesByRole((string) $user['role']);
    }

    /**
     * Cek apakah role memiliki permission tertentu
     */
    public function roleHasPermission(string $role, string $permissionName): bool
    {
        $role = $this->normalizeRole($role);

        $count = $this->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->where('role_permissions.role', $role)
            ->where('permissions.name', $permissionName)
            ->countAllResults();
        return $count > 0;
    }

    /**
     * Ambil semua permission dikelompokkan per grup
     */
    public function getGroupedPermissions(): array
    {
        $permissions = $this->orderBy('group', 'ASC')->orderBy('name', 'ASC')->findAll();
        $grouped = [];
        foreach ($permissions as $perm) {
            $grouped[$perm['group']][] = $perm;
        }
        return $grouped;
    }

    /**
     * Set permission untuk role tertentu
     */
    public function setRolePermissions(string $role, array $permissionIds): bool
    {
        $role = $this->normalizeRole($role);
        $db = \Config\Database::connect();

        // Hapus semua permission role ini
        $db->table('role_permissions')->where('role', $role)->delete();

        // Tambah permission baru
        foreach ($permissionIds as $permId) {
            $db->table('role_permissions')->insert([
                'role'          => $role,
                'permission_id' => $permId,
            ]);
        }

        return true;
    }

    /**
     * Ambil permission_ids untuk role tertentu
     */
    public function getRolePermissionIds(string $role): array
    {
        $role = $this->normalizeRole($role);
        $db = \Config\Database::connect();
        $results = $db->table('role_permissions')
            ->where('role', $role)
            ->get()
            ->getResultArray();
        return array_column($results, 'permission_id');
    }
}
