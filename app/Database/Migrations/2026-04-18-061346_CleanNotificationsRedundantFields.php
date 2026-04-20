<?php
/**
 * Cleanup - Hapus kolom redundan di tabel notifications
 * reference_type dan reference_id sudah digantikan oleh product_id, request_id, stock_movement_id
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanNotificationsRedundantFields extends Migration
{
    public function up()
    {
        // 1. Hapus reference_type (sudah diganti kolom spesifik)
        if ($this->db->fieldExists('reference_type', 'notifications')) {
            $this->forge->dropColumn('notifications', 'reference_type');
        }

        // 2. Hapus reference_id (sudah diganti kolom spesifik)
        if ($this->db->fieldExists('reference_id', 'notifications')) {
            $this->forge->dropColumn('notifications', 'reference_id');
        }
    }

    public function down()
    {
        // Re-add columns
        $this->forge->addColumn('notifications', [
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'url',
            ],
            'reference_id'   => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'reference_type',
            ],
        ]);
    }
}
