<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitIdToKriteriaUigm extends Migration
{
    public function up()
    {
        // Tambah kolom unit_id ke tabel kriteria_uigm
        $this->forge->addColumn('kriteria_uigm', [
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'user_id'
            ],
            'status_review' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
                'after' => 'gedung'
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'status_review'
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reviewed_by'
            ],
            'catatan_review' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'reviewed_at'
            ]
        ]);

        // Tambah foreign key constraints
        $this->forge->addForeignKey('unit_id', 'unit', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        // Drop foreign keys first
        $this->forge->dropForeignKey('kriteria_uigm', 'kriteria_uigm_unit_id_foreign');
        $this->forge->dropForeignKey('kriteria_uigm', 'kriteria_uigm_reviewed_by_foreign');
        
        // Drop columns
        $this->forge->dropColumn('kriteria_uigm', ['unit_id', 'status_review', 'reviewed_by', 'reviewed_at', 'catatan_review']);
    }
}