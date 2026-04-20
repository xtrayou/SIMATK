<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameProductsToBarang extends Migration
{
    public function up()
    {
        $this->forge->renameTable('products', 'barang');
    }

    public function down()
    {
        $this->forge->renameTable('barang', 'products');
    }
}
