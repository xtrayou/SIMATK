<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SyncRequestStatusFromDistributionMovements extends Migration
{
    public function up()
    {
        // Jika sudah ada mutasi keluar dengan ref REQ-{id}, tandai request sebagai distributed.
        $this->db->query("
            UPDATE `requests` r
            SET r.`status` = 'distributed',
                r.`updated_at` = NOW()
            WHERE r.`status` IN ('requested', 'approved')
              AND EXISTS (
                SELECT 1
                FROM `stock_movements` m
                WHERE m.`type` = 'OUT'
                  AND m.`reference_no` = CONCAT('REQ-', r.`id`)
              )
        ");
    }

    public function down()
    {
        // Tidak mengembalikan otomatis karena ini adalah data-correction migration.
    }
}

