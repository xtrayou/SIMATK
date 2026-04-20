<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixForeignKeysAndNotificationsReadBy extends Migration
{
    public function up()
    {
        $this->recreateForeignKey(
            'products',
            'products_category_id_foreign',
            'category_id',
            'categories',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->recreateForeignKey(
            'request_items',
            'request_items_product_id_foreign',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->recreateForeignKey(
            'stock_movements',
            'stock_movements_product_id_foreign',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->recreateForeignKey(
            'stock_movements',
            'stock_movements_created_by_foreign',
            'created_by',
            'users',
            'id',
            'CASCADE',
            'SET NULL'
        );

        // Pastikan tidak ada read_by yatim sebelum FK ditambahkan.
        $this->db->query(
            'UPDATE `notifications` n
             LEFT JOIN `users` u ON u.id = n.read_by
             SET n.read_by = NULL
             WHERE n.read_by IS NOT NULL AND u.id IS NULL'
        );

        if (!$this->foreignKeyExists('notifications', 'notifications_read_by_foreign')) {
            $this->db->query(
                'ALTER TABLE `notifications`
                 ADD CONSTRAINT `notifications_read_by_foreign`
                 FOREIGN KEY (`read_by`) REFERENCES `users` (`id`)
                 ON UPDATE CASCADE
                 ON DELETE SET NULL'
            );
        }
    }

    public function down()
    {
        if ($this->foreignKeyExists('notifications', 'notifications_read_by_foreign')) {
            $this->forge->dropForeignKey('notifications', 'notifications_read_by_foreign');
        }

        $this->recreateForeignKey(
            'products',
            'products_category_id_foreign',
            'category_id',
            'categories',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->recreateForeignKey(
            'request_items',
            'request_items_product_id_foreign',
            'product_id',
            'products',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->recreateForeignKey(
            'stock_movements',
            'stock_movements_product_id_foreign',
            'product_id',
            'products',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->recreateForeignKey(
            'stock_movements',
            'stock_movements_created_by_foreign',
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    private function recreateForeignKey(
        string $table,
        string $constraintName,
        string $column,
        string $refTable,
        string $refColumn,
        string $onUpdate,
        string $onDelete
    ): void {
        if ($this->foreignKeyExists($table, $constraintName)) {
            $this->forge->dropForeignKey($table, $constraintName);
        }

        $this->db->query(
            sprintf(
                'ALTER TABLE `%s`
                 ADD CONSTRAINT `%s`
                 FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`)
                 ON UPDATE %s
                 ON DELETE %s',
                $table,
                $constraintName,
                $column,
                $refTable,
                $refColumn,
                $onUpdate,
                $onDelete
            )
        );
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $query = $this->db->query(
            'SELECT COUNT(*) AS total
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND CONSTRAINT_NAME = ?
               AND CONSTRAINT_TYPE = "FOREIGN KEY"',
            [$table, $constraintName]
        );

        $row = $query->getRowArray();

        return (int) ($row['total'] ?? 0) > 0;
    }
}
