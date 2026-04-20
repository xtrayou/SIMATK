<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Refactor Database Cleanup
 *
 * Perubahan:
 * 1. Drop tabel kode_barang (data redundan dengan products.sku)
 * 2. Tambah user_id ke requests (siapa yang input permintaan)
 * 3. Ubah notifications.reference_type jadi ENUM
 */
class RefactorDatabaseCleanup extends Migration
{
    public function up()
    {
        // ── 1. Drop tabel kode_barang ────────────────────────────────────
        $this->forge->dropTable('kode_barang', true);

        // ── 2. Tambah user_id ke requests ────────────────────────────────
        if (!$this->db->fieldExists('user_id', 'requests')) {
            $this->forge->addColumn('requests', [
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                    'comment'    => 'ID pengguna yang menginput permintaan',
                ],
            ]);

            // Tambah index dan FK
            $this->db->query('ALTER TABLE `requests` ADD KEY `requests_user_id_index` (`user_id`)');

            // Hanya tambah FK jika belum ada
            if (!$this->foreignKeyExists('requests', 'requests_user_id_foreign')) {
                $this->db->query(
                    'ALTER TABLE `requests`
                     ADD CONSTRAINT `requests_user_id_foreign`
                     FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                     ON UPDATE CASCADE
                     ON DELETE SET NULL'
                );
            }
        }

        // ── 3. Ubah notifications.reference_type jadi ENUM ───────────────
        $this->forge->modifyColumn('notifications', [
            'reference_type' => [
                'type'       => 'ENUM',
                'constraint' => ['product', 'request', 'stock_movement'],
                'null'       => true,
                'comment'    => 'Tipe referensi polymorphic',
            ],
        ]);

        // ── 4. Update notifications.for_role ENUM (tambah 'superadmin') ──
        $this->forge->modifyColumn('notifications', [
            'for_role' => [
                'type'       => 'ENUM',
                'constraint' => ['superadmin', 'admin', 'user', 'all'],
                'default'    => 'all',
                'comment'    => 'Notifikasi ditujukan ke role tertentu',
            ],
        ]);
    }

    public function down()
    {
        // ── 1. Buat ulang tabel kode_barang ──────────────────────────────
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kode_barang');

        // ── 2. Hapus user_id dari requests ───────────────────────────────
        if ($this->foreignKeyExists('requests', 'requests_user_id_foreign')) {
            $this->forge->dropForeignKey('requests', 'requests_user_id_foreign');
        }
        if ($this->db->fieldExists('user_id', 'requests')) {
            $this->forge->dropColumn('requests', 'user_id');
        }

        // ── 3. Revert reference_type ke VARCHAR ──────────────────────────
        $this->forge->modifyColumn('notifications', [
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Tipe referensi: product, request, stock_movement',
            ],
        ]);

        // ── 4. Revert for_role ───────────────────────────────────────────
        $this->forge->modifyColumn('notifications', [
            'for_role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'staff', 'all'],
                'default'    => 'all',
                'comment'    => 'Notifikasi ditujukan ke role tertentu',
            ],
        ]);
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
