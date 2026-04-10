<?php

namespace App\Controllers;

use App\Models\HakAksesModel;

class HakAksesController extends BaseController
{
    protected HakAksesModel $modelHakAkses;

    public function __construct()
    {
        $this->modelHakAkses = new HakAksesModel();
    }

    /**
     * Halaman manajemen role-permission.
     */
    public function index()
    {
        $this->setPageData('Manajemen Hak Akses', 'Atur permission untuk setiap role');

        $roles = $this->modelHakAkses->getManageableRoles();
        $selectedRole = strtolower((string) ($this->request->getGet('role') ?? 'admin'));
        if (!in_array($selectedRole, $roles, true)) {
            $selectedRole = 'admin';
        }

        return $this->render('permissions/index', [
            'roles' => $roles,
            'selectedRole' => $selectedRole,
            'groupedPermissions' => $this->modelHakAkses->getGroupedPermissions(),
            'assignedPermissionIds' => $this->modelHakAkses->getRolePermissionIds($selectedRole),
        ]);
    }

    /**
     * Simpan role-permission.
     */
    public function update()
    {
        $roles = $this->modelHakAkses->getManageableRoles();
        $role = strtolower((string) $this->request->getPost('role'));

        if (!in_array($role, $roles, true)) {
            return redirect()->to('/permissions')->with('error', 'Role tidak valid.');
        }

        $permissionIds = $this->request->getPost('permissions');
        if (!is_array($permissionIds)) {
            $permissionIds = [];
        }

        $permissionIds = array_values(array_unique(array_map(static function ($id): int {
            return max(0, (int) $id);
        }, $permissionIds)));
        $permissionIds = array_values(array_filter($permissionIds, static fn(int $id): bool => $id > 0));

        $this->modelHakAkses->setRolePermissions($role, $permissionIds);

        // Sinkronkan session jika role yang sedang login diubah.
        if ((string) session()->get('role') === $role) {
            session()->set('permissions', $this->modelHakAkses->getPermissionNamesByRole($role));
        }

        return redirect()->to('/permissions?role=' . rawurlencode($role))
            ->with('success', 'Hak akses role berhasil diperbarui.');
    }
}
