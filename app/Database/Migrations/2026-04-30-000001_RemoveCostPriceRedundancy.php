<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveCostPriceRedundancy extends Migration
{
    public function up()
    {
        // Drop cost_price column as it is redundant with price
        if ($this->db->fieldExists('cost_price', 'barang')) {
            $this->forge->dropColumn('barang', 'cost_price');
        }
    }

    public function down()
    {
        // Restore cost_price column
        if (!$this->db->fieldExists('cost_price', 'barang')) {
            $this->forge->addColumn('barang', [
                'cost_price' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'price'
                ],
            ]);

            // Sync data from price to cost_price if possible
            $this->db->query('UPDATE barang SET cost_price = price');
        }
    }
}
