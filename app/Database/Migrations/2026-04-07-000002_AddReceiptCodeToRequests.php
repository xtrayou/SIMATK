<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReceiptCodeToRequests extends Migration
{
    public function up()
    {
        $this->forge->addColumn('requests', [
            'receipt_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'email',
            ],
        ]);

        $this->forge->addKey('receipt_code');
    }

    public function down()
    {
        $this->forge->dropColumn('requests', 'receipt_code');
    }
}

