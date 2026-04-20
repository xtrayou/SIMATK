<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameBorrowerIdentifier extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('borrower_identifier', 'requests')) {
            $this->forge->modifyColumn('requests', [
                'borrower_identifier' => [
                    'name'       => 'borrower_id_number',
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'comment'    => 'NIP, NIM, atau nomor identitas lain'
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('borrower_id_number', 'requests')) {
            $this->forge->modifyColumn('requests', [
                'borrower_id_number' => [
                    'name'       => 'borrower_identifier',
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ]
            ]);
        }
    }
}
