<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignRbacRoles extends Migration
{
    public function up()
    {
        // 1) Selaraskan data role user ke superadmin/admin.
        $this->db->query("UPDATE users SET role = 'admin' WHERE role IN ('staff', 'user', '') OR role IS NULL");
        $this->db->query("ALTER TABLE users MODIFY role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin'");

        // 2) Selaraskan role pada pivot permission.
        // Hapus entri legacy yang akan bentrok dengan entri admin existing.
        $this->db->query("DELETE rp_legacy FROM role_permissions rp_legacy INNER JOIN role_permissions rp_admin ON rp_admin.permission_id = rp_legacy.permission_id AND rp_admin.role = 'admin' WHERE rp_legacy.role IN ('staff', 'user')");
        // Deduplikasi entri legacy antar staff/user agar aman saat dipetakan ke admin.
        $this->db->query("DELETE rp1 FROM role_permissions rp1 INNER JOIN role_permissions rp2 ON rp1.id > rp2.id AND rp1.permission_id = rp2.permission_id AND rp1.role IN ('staff', 'user') AND rp2.role IN ('staff', 'user')");
        $this->db->query("UPDATE role_permissions SET role = 'admin' WHERE role IN ('staff', 'user')");
        $this->db->query("DELETE rp1 FROM role_permissions rp1 INNER JOIN role_permissions rp2 ON rp1.id > rp2.id AND rp1.role = rp2.role AND rp1.permission_id = rp2.permission_id");
        $this->db->query("ALTER TABLE role_permissions MODIFY role ENUM('superadmin', 'admin') NOT NULL");

        // 3) Pastikan permission khusus manajemen hak akses tersedia.
        $permission = $this->db->table('permissions')
            ->where('name', 'permissions.manage')
            ->get()
            ->getRowArray();

        if (!$permission) {
            $this->db->table('permissions')->insert([
                'name' => 'permissions.manage',
                'group' => 'users',
                'description' => 'Mengelola role dan hak akses',
            ]);

            $permission = $this->db->table('permissions')
                ->where('name', 'permissions.manage')
                ->get()
                ->getRowArray();
        }

        // 4) Berikan semua permission untuk admin dan superadmin.
        $allPermissions = $this->db->table('permissions')->select('id')->get()->getResultArray();
        foreach ($allPermissions as $perm) {
            foreach (['admin', 'superadmin'] as $role) {
                $exists = $this->db->table('role_permissions')
                    ->where('role', $role)
                    ->where('permission_id', $perm['id'])
                    ->countAllResults();

                if ($exists === 0) {
                    $this->db->table('role_permissions')->insert([
                        'role' => $role,
                        'permission_id' => $perm['id'],
                    ]);
                }
            }
        }

        // 5) Selaraskan target role notifikasi.
        $this->db->query("UPDATE notifications SET for_role = 'admin' WHERE for_role IN ('staff', 'user')");
        $this->db->query("ALTER TABLE notifications MODIFY for_role ENUM('superadmin', 'admin', 'all') NOT NULL DEFAULT 'all'");

        // 6) Sinkronkan komposisi stok baik/rusak terhadap stok total.
        $this->db->query('UPDATE products SET stock_baik = current_stock WHERE stock_baik IS NULL OR stock_baik < 0');
        $this->db->query('UPDATE products SET stock_rusak = 0 WHERE stock_rusak IS NULL OR stock_rusak < 0');
        $this->db->query('UPDATE products SET stock_baik = current_stock, stock_rusak = 0 WHERE (stock_baik + stock_rusak) <> current_stock');
    }

    public function down()
    {
        // Kembalikan skema users ke versi sebelum alignment.
        $this->db->query("ALTER TABLE users MODIFY role ENUM('superadmin', 'admin', 'user') NOT NULL DEFAULT 'user'");

        // Kembalikan skema role_permissions ke versi lama.
        // Hindari bentrok unique key saat superadmin dipetakan ulang ke admin.
        $this->db->query("DELETE rp_super FROM role_permissions rp_super INNER JOIN role_permissions rp_admin ON rp_admin.permission_id = rp_super.permission_id AND rp_admin.role = 'admin' WHERE rp_super.role = 'superadmin'");
        $this->db->query("UPDATE role_permissions SET role = 'admin' WHERE role = 'superadmin'");
        $this->db->query("DELETE rp1 FROM role_permissions rp1 INNER JOIN role_permissions rp2 ON rp1.id > rp2.id AND rp1.role = rp2.role AND rp1.permission_id = rp2.permission_id");
        $this->db->query("ALTER TABLE role_permissions MODIFY role ENUM('admin', 'staff') NOT NULL");

        // Hapus permission manajemen role jika ada.
        $perm = $this->db->table('permissions')->where('name', 'permissions.manage')->get()->getRowArray();
        if ($perm) {
            $this->db->table('role_permissions')->where('permission_id', $perm['id'])->delete();
            $this->db->table('permissions')->where('id', $perm['id'])->delete();
        }

        // Kembalikan enum notifikasi ke versi lama.
        $this->db->query("UPDATE notifications SET for_role = 'admin' WHERE for_role = 'superadmin'");
        $this->db->query("ALTER TABLE notifications MODIFY for_role ENUM('admin', 'staff', 'all') NOT NULL DEFAULT 'all'");
    }
}
