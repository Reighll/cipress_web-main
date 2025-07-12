<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTable extends Migration
{
    public function up()
    {
        // Check if the table already exists
        if (!$this->db->tableExists('staff')) {
            // If table doesn't exist, create it with all columns
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
            // If table exists, check for and add each missing column individually.
            $fieldsToAdd = [];

            if (!$this->db->fieldExists('staff_status', 'staff')) {
                $fieldsToAdd['staff_status'] = [
                    'type' => 'ENUM("pending", "approved", "declined")',
                    'default' => 'pending',
                    'null' => false,
                    'after' => 'staff_password'
                ];
            }
            if (!$this->db->fieldExists('last_clock_in', 'staff')) {
                $fieldsToAdd['last_clock_in'] = ['type' => 'DATETIME', 'null' => true, 'after' => 'staff_status'];
            }
            if (!$this->db->fieldExists('last_clock_out', 'staff')) {
                $fieldsToAdd['last_clock_out'] = ['type' => 'DATETIME', 'null' => true, 'after' => 'last_clock_in'];
            }
            if (!$this->db->fieldExists('created_at', 'staff')) {
                $fieldsToAdd['created_at'] = ['type' => 'DATETIME', 'null' => true, 'after' => 'last_clock_out'];
            }
            if (!$this->db->fieldExists('updated_at', 'staff')) {
                $fieldsToAdd['updated_at'] = ['type' => 'DATETIME', 'null' => true, 'after' => 'created_at'];
            }

            if (!empty($fieldsToAdd)) {
                $this->forge->addColumn('staff', $fieldsToAdd);
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('staff', true);
    }
}
