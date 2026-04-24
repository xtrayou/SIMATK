<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambah kolom kondisi barang (baik & rusak) ke tabel stock_opname_archives.
 * Sebelumnya, tabel hanya menyimpan total kuantitas (quantity) tanpa pemisahan
 * kondisi baik/rusak. Migration ini memisahkannya agar histori dapat dilacak.
 */
class AddConditionColumnsToStockOpnameArchives extends Migration
{
    public function up()
    {
        // Tambah dua kolom baru setelah kolom 'quantity'
        $fields = [
            'good_quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'quantity',
                'comment'    => 'Jumlah barang dalam kondisi baik saat stock opname',
            ],
            'damaged_quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'good_quantity',
                'comment'    => 'Jumlah barang rusak/usang saat stock opname',
            ],
        ];

        $this->forge->addColumn('stock_opname_archives', $fields);

        // Backfill: data lama dianggap semua stok baik (quantity = good_quantity)
        $this->db->query('UPDATE stock_opname_archives SET good_quantity = quantity, damaged_quantity = 0 WHERE good_quantity = 0 AND damaged_quantity = 0');
    }

    public function down()
    {
        $this->forge->dropColumn('stock_opname_archives', 'good_quantity');
        $this->forge->dropColumn('stock_opname_archives', 'damaged_quantity');
    }
}
