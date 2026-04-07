<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixRequestStatusDataAfterEnumAlign extends Migration
{
    public function up()
    {
        // Buka sementara enum agar nilai lama+baru bisa hidup bersama saat mapping.
        $this->db->query("
            ALTER TABLE `requests`
            MODIFY `status` ENUM('pending','approved','canceled','returned','requested','cancelled','distributed')
            NULL
        ");

        // Mapping final ke istilah status aplikasi.
        $this->db->query("UPDATE `requests` SET `status` = 'requested' WHERE `status` IS NULL OR `status` = '' OR `status` = 'pending'");
        $this->db->query("UPDATE `requests` SET `status` = 'cancelled' WHERE `status` = 'canceled'");
        $this->db->query("UPDATE `requests` SET `status` = 'distributed' WHERE `status` = 'returned'");

        // Kunci kembali ke enum final.
        $this->db->query("
            ALTER TABLE `requests`
            MODIFY `status` ENUM('requested','approved','distributed','cancelled')
            NOT NULL DEFAULT 'requested'
        ");
    }

    public function down()
    {
        // Rollback sederhana ke enum lama.
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

