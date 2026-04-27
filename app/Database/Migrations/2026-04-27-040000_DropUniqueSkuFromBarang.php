<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUniqueSkuFromBarang extends Migration
{
    public function up()
    {
        // Drop the unique constraint on the `sku` column
        // The index name is typically the column name `sku`
        $this->forge->processIndexes('barang'); // A trick if CodeIgniter needs to sync
        $this->db->query('ALTER TABLE barang DROP INDEX sku');
        
        // Optionally, we could add a regular index back to 'sku' to keep searches fast
        $this->db->query('ALTER TABLE barang ADD INDEX sku_index (sku)');
    }

    public function down()
    {
        // Revert: drop the regular index and add unique constraint back
        $this->db->query('ALTER TABLE barang DROP INDEX sku_index');
        $this->db->query('ALTER TABLE barang ADD UNIQUE sku (sku)');
    }
}
