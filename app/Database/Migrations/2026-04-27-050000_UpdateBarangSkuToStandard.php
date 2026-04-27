<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBarangSkuToStandard extends Migration
{
    public function up()
    {
        // Potong SKU yang panjangnya lebih dari 10 karakter (misal 13 karakter) 
        // menjadi 10 karakter pertama (kode baku pemerintah)
        $this->db->query("UPDATE barang SET sku = SUBSTRING(sku, 1, 10) WHERE LENGTH(sku) > 10");
    }

    public function down()
    {
        // Tidak dapat di-revert secara pasti karena suffix unik sudah hilang
    }
}
