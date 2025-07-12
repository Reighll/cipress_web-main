<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSaleItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'sale_item_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sale_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            // Price of the item at the time of sale
            'item_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->forge->addKey('sale_item_id', true);
        $this->forge->addForeignKey('sale_id', 'sales', 'sale_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'item_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sale_items');
    }

    public function down()
    {
        $this->forge->dropTable('sale_items');
    }
}
