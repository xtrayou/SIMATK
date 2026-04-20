<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpecificReferenceIdsToNotifications extends Migration
{
    public function up()
    {
        $fields = [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'url',
            ],
            'request_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'product_id',
            ],
            'stock_movement_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'request_id',
            ],
        ];

        $this->forge->addColumn('notifications', $fields);

        // Data migration
        $this->db->query("UPDATE notifications SET product_id = reference_id WHERE reference_type = 'product'");
        $this->db->query("UPDATE notifications SET request_id = reference_id WHERE reference_type = 'request'");
        $this->db->query("UPDATE notifications SET stock_movement_id = reference_id WHERE reference_type = 'stock_movement'");

        // Add Foreign Keys
        $this->db->query("ALTER TABLE notifications ADD CONSTRAINT fk_notifications_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE notifications ADD CONSTRAINT fk_notifications_request_id FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE notifications ADD CONSTRAINT fk_notifications_stock_movement_id FOREIGN KEY (stock_movement_id) REFERENCES stock_movements(id) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->forge->dropForeignKey('notifications', 'fk_notifications_product_id');
        $this->forge->dropForeignKey('notifications', 'fk_notifications_request_id');
        $this->forge->dropForeignKey('notifications', 'fk_notifications_stock_movement_id');
        
        $this->forge->dropColumn('notifications', ['product_id', 'request_id', 'stock_movement_id']);
    }
}
