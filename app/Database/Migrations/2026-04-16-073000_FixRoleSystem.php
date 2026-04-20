<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Fix Role System
 *
 * Perubahan:
 * 1. Tambah role_id FK di users → roles.id
 * 2. Sinkronisasi data existing users.role → role_id
 * 3. Hapus kolom permissions di roles (redundan karena ada role_permissions)
 */
class FixRoleSystem extends Migration
{
    public function up()
    {
        // ── 1. Tambah role_id ke users ────────────────────────────────────
        if (!$this->db->fieldExists('role_id', 'users')) {
            $this->forge->addColumn('users', [
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'name',
                    'comment'    => 'FK ke roles.id',
                ],
            ]);

            // Sinkronisasi data: set role_id berdasarkan users.role → roles.name
            $this->db->query(
                "UPDATE `users` u
                 INNER JOIN `roles` r ON r.name = u.role
                 SET u.role_id = r.id"
            );

            // Tambah index dan FK
            $this->db->query('ALTER TABLE `users` ADD KEY `users_role_id_index` (`role_id`)');

            if (!$this->foreignKeyExists('users', 'users_role_id_foreign')) {
                $this->db->query(
                    'ALTER TABLE `users`
                     ADD CONSTRAINT `users_role_id_foreign`
                     FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
                     ON UPDATE CASCADE
                     ON DELETE SET NULL'
                );
            }
        }

        // ── 2. Hapus kolom permissions di roles (redundan) ───────────────
        if ($this->db->fieldExists('permissions', 'roles')) {
            $this->forge->dropColumn('roles', 'permissions');
        }
    }

    public function down()
    {
        // ── 1. Tambah kembali permissions di roles ───────────────────────
        if (!$this->db->fieldExists('permissions', 'roles')) {
            $this->forge->addColumn('roles', [
                'permissions' => [
                    'type'  => 'TEXT',
                    'null'  => true,
                    'after' => 'label',
                ],
            ]);
        }

        // ── 2. Hapus role_id dari users ──────────────────────────────────
        if ($this->foreignKeyExists('users', 'users_role_id_foreign')) {
            $this->forge->dropForeignKey('users', 'users_role_id_foreign');
        }
        if ($this->db->fieldExists('role_id', 'users')) {
            $this->forge->dropColumn('users', 'role_id');
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
