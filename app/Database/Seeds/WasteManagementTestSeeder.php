<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WasteManagementTestSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have an admin_unit user
        $adminUnit = $this->db->table('users')->where('role', 'admin_unit')->get()->getRowArray();

        if (!$adminUnit) {
            echo "Creating admin_unit user...\n";

            // Get a unit to assign
            $unit = $this->db->table('unit')->where('status_aktif', true)->get()->getRowArray();
            if (!$unit) {
                echo "No active unit found. Creating test unit...\n";
                $this->db->table('unit')->insert([
                    'kode_unit' => 'TI',
                    'nama_unit' => 'Teknik Informatika',
                    'tipe_unit' => 'akademik',
                    'status_aktif' => true,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $unitId = $this->db->insertID();
            } else {
                $unitId = $unit['id'];
            }

            // Create admin_unit user
            $this->db->table('users')->insert([
                'username' => 'admin_unit',
                'password' => 'password123', // Plain text as requested
                'email' => 'admin.unit@uigm.ac.id',
                'nama_lengkap' => 'Admin Unit Teknik Informatika',
                'role' => 'admin_unit',
                'unit_id' => $unitId,
                'status_aktif' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $adminUnit = $this->db->table('users')->where('role', 'admin_unit')->get()->getRowArray();
        }

        // Get active year
        $tahunAktif = $this->db->table('tahun_penilaian')->where('status_aktif', true)->get()->getRowArray();

        if (!$tahunAktif) {
            echo "Creating active assessment year...\n";
            $this->db->table('tahun_penilaian')->insert([
                'tahun' => date('Y'),
                'nama_periode' => 'Periode ' . date('Y'),
                'tanggal_mulai' => date('Y-01-01'),
                'tanggal_selesai' => date('Y-12-31'),
                'status_aktif' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $tahunAktif = $this->db->table('tahun_penilaian')->where('status_aktif', true)->get()->getRowArray();
        }

        // Check if pengiriman already exists
        $existingPengiriman = $this->db->table('pengiriman_unit')
            ->where('unit_id', $adminUnit['unit_id'])
            ->where('tahun_penilaian_id', $tahunAktif['id'])
            ->get()->getRowArray();

        if (!$existingPengiriman) {
            echo "Creating pengiriman record for admin unit...\n";
            $this->db->table('pengiriman_unit')->insert([
                'unit_id' => $adminUnit['unit_id'],
                'tahun_penilaian_id' => $tahunAktif['id'],
                'status_pengiriman' => 'draft',
                'progress_persen' => 0.0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Ensure we have UIGM categories
        $categories = $this->db->table('indikator')->where('status_aktif', true)->get()->getResultArray();

        if (count($categories) < 6) {
            echo "Creating UIGM categories...\n";

            $uigmCategories = [
                [
                    'kode_kategori' => 'SI',
                    'nama_kategori' => 'Setting & Infrastructure',
                    'deskripsi' => 'Pengaturan dan Infrastruktur Kampus Hijau',
                    'bobot' => 15,
                    'warna' => '#28a745',
                    'status_aktif' => true
                ],
                [
                    'kode_kategori' => 'EC',
                    'nama_kategori' => 'Energy & Climate Change',
                    'deskripsi' => 'Energi dan Perubahan Iklim',
                    'bobot' => 21,
                    'warna' => '#ffc107',
                    'status_aktif' => true
                ],
                [
                    'kode_kategori' => 'WS',
                    'nama_kategori' => 'Waste',
                    'deskripsi' => 'Pengelolaan Sampah',
                    'bobot' => 18,
                    'warna' => '#17a2b8',
                    'status_aktif' => true
                ],
                [
                    'kode_kategori' => 'WR',
                    'nama_kategori' => 'Water',
                    'deskripsi' => 'Pengelolaan Air',
                    'bobot' => 10,
                    'warna' => '#007bff',
                    'status_aktif' => true
                ],
                [
                    'kode_kategori' => 'TR',
                    'nama_kategori' => 'Transportation',
                    'deskripsi' => 'Transportasi Berkelanjutan',
                    'bobot' => 18,
                    'warna' => '#6f42c1',
                    'status_aktif' => true
                ],
                [
                    'kode_kategori' => 'ED',
                    'nama_kategori' => 'Education',
                    'deskripsi' => 'Pendidikan Lingkungan',
                    'bobot' => 18,
                    'warna' => '#fd7e14',
                    'status_aktif' => true
                ]
            ];

            foreach ($uigmCategories as $category) {
                // Check if category already exists
                $existing = $this->db->table('indikator')
                    ->where('kode_kategori', $category['kode_kategori'])
                    ->get()->getRowArray();

                if (!$existing) {
                    $category['created_at'] = date('Y-m-d H:i:s');
                    $category['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->table('indikator')->insert($category);
                }
            }
        }

        echo "Waste Management Test Data Setup Complete!\n";
        echo "You can now test with:\n";
        echo "- Admin Unit: username 'admin_unit', password 'password123'\n";
        echo "- Admin Pusat: username 'admin_pusat', password 'password123'\n";
        echo "- All UIGM categories are available for data input\n";
    }
}
