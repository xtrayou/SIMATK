<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Fix Redundant Role Fields
 *
 * Perubahan:
 * 1. Hapus kolom users.role (varchar) — redundan, cukup pakai role_id FK
 * 2. Ganti role_permissions.role (enum) → role_id (int FK ke roles.id)
 * 3. Ubah notifications.for_role dari ENUM menjadi VARCHAR(50) — hapus kekakuan enum
 */
class FixRedundantRoleFields extends Migration
{
    public function up()
    {
        // ── 1. Hapus users.role (varchar) ────────────────────────────────

        // Pastikan semua users yang belum punya role_id sudah disinkronisasi dulu
        if ($this->db->fieldExists('role', 'users')) {
            $this->db->query(
                "UPDATE `users` u
                 INNER JOIN `roles` r ON r.name = u.role
                 SET u.role_id = r.id
                 WHERE u.role_id IS NULL"
            );
            $this->forge->dropColumn('users', 'role');
        }

        // ── 2. Ganti role_permissions.role (enum) → role_id (int FK) ─────

        if ($this->db->fieldExists('role', 'role_permissions') && ! $this->db->fieldExists('role_id', 'role_permissions')) {
            // 2a. Tambah kolom role_id baru
            $this->forge->addColumn('role_permissions', [
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                    'comment'    => 'FK ke roles.id',
                ],
            ]);

            // 2b. Migrasi data: role name → role_id
            $this->db->query(
                "UPDATE `role_permissions` rp
                 INNER JOIN `roles` r ON r.name = rp.role
                 SET rp.role_id = r.id"
            );

            // 2c. Hapus index lama lalu kolom role (enum)
            $this->db->query('ALTER TABLE `role_permissions` DROP INDEX `role_permission_id`');
            $this->forge->dropColumn('role_permissions', 'role');

            // 2d. Set role_id NOT NULL setelah data terisi
            $this->forge->modifyColumn('role_permissions', [
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                ],
            ]);

            // 2e. Tambah index dan FK
            $this->db->query('ALTER TABLE `role_permissions` ADD UNIQUE KEY `role_id_permission_id` (`role_id`, `permission_id`)');
            $this->db->query('ALTER TABLE `role_permissions` ADD KEY `rp_role_id_index` (`role_id`)');

            if (! $this->foreignKeyExists('role_permissions', 'rp_role_id_foreign')) {
                $this->db->query(
                    'ALTER TABLE `role_permissions`
                     ADD CONSTRAINT `rp_role_id_foreign`
                     FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
                     ON UPDATE CASCADE
                     ON DELETE CASCADE'
                );
            }
        }

        // ── 3. Ubah notifications.for_role dari ENUM → VARCHAR(50) ───────
        // Menghilangkan kekakuan enum hardcoded, nilai string tetap 'superadmin','admin','all'
        $this->forge->modifyColumn('notifications', [
            'for_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'all',
                'comment'    => 'Target role notifikasi: superadmin, admin, all (tidak di-enum agar fleksibel)',
            ],
        ]);
    }

    public function down()
    {
        // ── 3. Revert notifications.for_role ke ENUM ─────────────────────
        $this->forge->modifyColumn('notifications', [
            'for_role' => [
                'type'       => 'ENUM',
                'constraint' => ['superadmin', 'admin', 'user', 'all'],
                'default'    => 'all',
            ],
        ]);

        // ── 2. Revert role_permissions: role_id → role (enum) ────────────
        if ($this->db->fieldExists('role_id', 'role_permissions') && ! $this->db->fieldExists('role', 'role_permissions')) {
            $this->forge->addColumn('role_permissions', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['superadmin', 'admin'],
                    'null'       => true,
                    'after'      => 'id',
                ],
            ]);

            $this->db->query(
                "UPDATE `role_permissions` rp
                 INNER JOIN `roles` r ON r.id = rp.role_id
                 SET rp.role = r.name"
            );

            $this->db->query('ALTER TABLE `role_permissions` DROP INDEX `role_id_permission_id`');
            
            if ($this->foreignKeyExists('role_permissions', 'rp_role_id_foreign')) {
                $this->forge->dropForeignKey('role_permissions', 'rp_role_id_foreign');
            }
            $this->forge->dropColumn('role_permissions', 'role_id');

            $this->forge->modifyColumn('role_permissions', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['superadmin', 'admin'],
                    'null'       => false,
                ],
            ]);
            
            $this->db->query('ALTER TABLE `role_permissions` ADD UNIQUE KEY `role_permission_id` (`role`, `permission_id`)');
        }

        // ── 1. Revert users: tambah kembali kolom role (varchar) ──────────
        if (! $this->db->fieldExists('role', 'users')) {
            $this->forge->addColumn('users', [
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'default'    => 'admin',
                    'after'      => 'name',
                ],
            ]);

            $this->db->query(
                "UPDATE `users` u
                 INNER JOIN `roles` r ON r.id = u.role_id
                 SET u.role = r.name"
            );
        }
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $query = $this->db->query(
            'SELECT COUNT(*) AS total
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND CONSTRAINT_NAME = ?
               AND CONSTRAINT_TYPE = "FOREIGN KEY"',
            [$table, $constraintName]
        );

        $row = $query->getRowArray();

        return (int) ($row['total'] ?? 0) > 0;
    }
}
