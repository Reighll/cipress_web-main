<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOwnerTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'owner_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'owner_firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'owner_lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'owner_username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'owner_password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'owner_systemkey' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('owner_id', true);
        // The 'true' parameter checks if the table exists before creating it.
        $this->forge->createTable('owner', true);
    }

    public function down()
    {
        // The 'true' parameter checks if the table exists before dropping it.
        $this->forge->dropTable('owner', true);
    }
}
