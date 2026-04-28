<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKodeBarangTableFix extends Migration
{
    public function up()
    {
        // Drop table if exists to be safe
        $this->forge->dropTable('kode_barang', true);
        
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
    }

    public function down()
    {
        $this->forge->dropTable('kode_barang');
    }
}
