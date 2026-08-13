<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePropertiesEnumColumns extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('properties', [
            'parking' => [
                'name'       => 'parking',
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'default'    => 'Not Available',
            ],
            'basement' => [
                'name'       => 'basement',
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
                'default'    => 'No',
            ],
            'water_facility' => [
                'name'       => 'water_facility',
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down()
    {
        // Matches the original definitions in CreateProperties exactly.
        $this->forge->modifyColumn('properties', [
            'parking' => [
                'name'       => 'parking',
                'type'       => 'ENUM',
                'constraint' => ['Y', 'N'],
                'null'       => false,
                'default'    => 'N',
            ],
            'basement' => [
                'name'       => 'basement',
                'type'       => 'ENUM',
                'constraint' => ['Y', 'N'],
                'null'       => false,
                'default'    => 'N',
            ],
            'water_facility' => [
                'name'       => 'water_facility',
                'type'       => 'ENUM',
                'constraint' => ['Y', 'N'],
                'null'       => false,
                'default'    => 'N',
            ],
        ]);
    }
}