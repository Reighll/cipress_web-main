<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTable extends Migration
{
    public function up()
    {
        // Check if the table already exists before trying to create it
        if (!$this->db->tableExists('staff')) {
            $this->forge->addField([
                'staff_id' => [
                    'type'           => 'INT',
                    'constraint'     => 5,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'staff_firstname' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'staff_lastname' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'staff_username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'unique'     => true,
                ],
                'staff_password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'staff_status' => [
                    'type'    => 'ENUM("pending", "approved", "declined")',
                    'default' => 'pending',
                    'null'    => false,
                ],
                'last_clock_in' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'last_clock_out' => [
                    'type' => 'DATETIME',
                    'null' => true,
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
            $this->forge->addKey('staff_id', true);
            $this->forge->createTable('staff');
        } else {
            // If the table exists, add the new columns if they don't exist
            $this->forge->addColumn('staff', [
                'staff_status' => [
                    'type'    => 'ENUM("pending", "approved", "declined")',
                    'default' => 'pending',
                    'null'    => false,
                    'after'   => 'staff_password'
                ],
                'last_clock_in' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'staff_status'
                ],
                'last_clock_out' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'last_clock_in'
                ],
            ]);
        }
    }

    public function down()
    {
        // The 'true' parameter checks if the table exists before dropping it.
        $this->forge->dropTable('staff', true);
    }
}
