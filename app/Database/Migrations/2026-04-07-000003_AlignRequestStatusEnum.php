<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignRequestStatusEnum extends Migration
{
    public function up()
    {
        // Normalisasi nilai lama/invalid ke nilai yang digunakan aplikasi saat ini.
        $this->db->query("UPDATE `requests` SET `status` = 'requested' WHERE `status` IS NULL OR `status` = '' OR `status` = 'pending'");
        $this->db->query("UPDATE `requests` SET `status` = 'cancelled' WHERE `status` = 'canceled'");
        $this->db->query("UPDATE `requests` SET `status` = 'distributed' WHERE `status` = 'returned'");

        // Samakan ENUM dengan status yang dipakai controller/view.
        $this->db->query("
            ALTER TABLE `requests`
            MODIFY `status` ENUM('requested','approved','distributed','cancelled')
            NOT NULL DEFAULT 'requested'
        ");
    }

    public function down()
    {
        // Kembalikan ke format lama bila rollback diperlukan.
        $this->db->query("UPDATE `requests` SET `status` = 'pending' WHERE `status` = 'requested'");
        $this->db->query("UPDATE `requests` SET `status` = 'canceled' WHERE `status` = 'cancelled'");
        $this->db->query("UPDATE `requests` SET `status` = 'returned' WHERE `status` = 'distributed'");

        $this->db->query("
            ALTER TABLE `requests`
            MODIFY `status` ENUM('pending','approved','canceled','returned')
            NOT NULL DEFAULT 'pending'
        ");
    }
}

