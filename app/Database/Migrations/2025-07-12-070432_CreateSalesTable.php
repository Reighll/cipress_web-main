<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'sale_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Optional customer
            ],
            'staff_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_received' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'change_due' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'sale_date' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('sale_id', true);
        $this->forge->addForeignKey('customer_id', 'customers', 'customer_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('staff_id', 'staff', 'staff_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sales');
    }

    public function down()
    {
        $this->forge->dropTable('sales');
    }
}
