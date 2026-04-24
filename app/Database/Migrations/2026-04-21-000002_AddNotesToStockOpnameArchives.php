<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambah kolom notes ke stock_opname_archives
 * untuk menyimpan keterangan/catatan saat arsip dibuat.
 */
class AddNotesToStockOpnameArchives extends Migration
{
    public function up()
    {
        $this->forge->addColumn('stock_opname_archives', [
            'notes' => [
                'type'    => 'TEXT',
                'null'    => true,
                'after'   => 'period_year',
                'comment' => 'Catatan tambahan saat arsip stock opname disimpan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('stock_opname_archives', 'notes');
    }
}
