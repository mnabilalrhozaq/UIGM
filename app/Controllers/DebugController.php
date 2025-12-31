<?php

namespace App\Controllers;

use App\Models\UnitModel;
use App\Models\TahunPenilaianModel;
use App\Models\PengirimanUnitModel;
use App\Models\ReviewKategoriModel;
use App\Models\IndikatorModel;

class DebugController extends BaseController
{
    public function testSave()
    {
        // Simulasi data yang dikirim dari form
        $testData = [
            'pengiriman_id' => 1,
            'indikator_id' => 1,
            'data_input' => json_encode([
                'tanggal_input' => date('Y-m-d'),
                'gedung' => 'Gedung A',
                'jumlah' => 100,
                'satuan' => 'kg',
                'deskripsi' => 'Test data untuk debugging sistem UIGM'
            ])
        ];

        echo "<h1>Debug Test Save Data</h1>";
        echo "<h2>Data yang akan disimpan:</h2>";
        echo "<pre>" . print_r($testData, true) . "</pre>";

        try {
            // Test database connection
            $db = \Config\Database::connect();
            echo "<h2>✅ Koneksi Database: Berhasil</h2>";

            // Test models
            $pengirimanModel = new PengirimanUnitModel();
            $reviewModel = new ReviewKategoriModel();
            $indikatorModel = new IndikatorModel();

            echo "<h2>✅ Models: Berhasil dimuat</h2>";

            // Check pengiriman exists
            $pengiriman = $pengirimanModel->find($testData['pengiriman_id']);
            if ($pengiriman) {
                echo "<h2>✅ Pengiriman ditemukan:</h2>";
                echo "<pre>" . print_r($pengiriman, true) . "</pre>";
            } else {
                echo "<h2>❌ Pengiriman tidak ditemukan dengan ID: " . $testData['pengiriman_id'] . "</h2>";
                return;
            }

            // Check indikator exists
            $indikator = $indikatorModel->find($testData['indikator_id']);
            if ($indikator) {
                echo "<h2>✅ Indikator ditemukan:</h2>";
                echo "<pre>" . print_r($indikator, true) . "</pre>";
            } else {
                echo "<h2>❌ Indikator tidak ditemukan dengan ID: " . $testData['indikator_id'] . "</h2>";
                return;
            }

            // Check existing review
            $existingReview = $reviewModel
                ->where('pengiriman_id', $testData['pengiriman_id'])
                ->where('indikator_id', $testData['indikator_id'])
                ->first();

            if ($existingReview) {
                echo "<h2>📝 Review yang sudah ada:</h2>";
                echo "<pre>" . print_r($existingReview, true) . "</pre>";
            } else {
                echo "<h2>📝 Belum ada review, akan membuat baru</h2>";
            }

            // Test save
            $reviewData = [
                'pengiriman_id' => $testData['pengiriman_id'],
                'indikator_id' => $testData['indikator_id'],
                'status_review' => 'pending',
                'data_input' => $testData['data_input']
            ];

            if ($existingReview) {
                $result = $reviewModel->update($existingReview['id'], $reviewData);
                echo "<h2>🔄 Update Review:</h2>";
                echo "Result: " . ($result ? "✅ Berhasil" : "❌ Gagal") . "<br>";
            } else {
                $result = $reviewModel->insert($reviewData);
                echo "<h2>➕ Insert Review Baru:</h2>";
                echo "Result: " . ($result ? "✅ Berhasil (ID: $result)" : "❌ Gagal") . "<br>";
            }

            // Check final result
            $finalReview = $reviewModel
                ->where('pengiriman_id', $testData['pengiriman_id'])
                ->where('indikator_id', $testData['indikator_id'])
                ->first();

            if ($finalReview) {
                echo "<h2>✅ Data Final Tersimpan:</h2>";
                echo "<pre>" . print_r($finalReview, true) . "</pre>";
            } else {
                echo "<h2>❌ Data tidak tersimpan</h2>";
            }
        } catch (\Exception $e) {
            echo "<h2>❌ Error:</h2>";
            echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }

    public function checkSession()
    {
        echo "<h1>Debug Session & User</h1>";

        $session = session();
        $user = $session->get('user');

        echo "<h2>Session Data:</h2>";
        echo "<pre>" . print_r($session->get(), true) . "</pre>";

        echo "<h2>User Data:</h2>";
        if ($user) {
            echo "<pre>" . print_r($user, true) . "</pre>";
        } else {
            echo "<p style='color: red;'>❌ User tidak ditemukan dalam session</p>";
        }

        echo "<h2>Is Logged In:</h2>";
        echo $session->get('isLoggedIn') ? "✅ Ya" : "❌ Tidak";
    }

    public function checkDatabase()
    {
        echo "<h1>Debug Database</h1>";

        try {
            $db = \Config\Database::connect();

            // Check pengiriman_unit
            $pengiriman = $db->query("SELECT * FROM pengiriman_unit ORDER BY id DESC LIMIT 5")->getResultArray();
            echo "<h2>Pengiriman Unit (5 terbaru):</h2>";
            echo "<pre>" . print_r($pengiriman, true) . "</pre>";

            // Check review_kategori
            $reviews = $db->query("SELECT * FROM review_kategori ORDER BY id DESC LIMIT 5")->getResultArray();
            echo "<h2>Review Kategori (5 terbaru):</h2>";
            echo "<pre>" . print_r($reviews, true) . "</pre>";

            // Check users
            $users = $db->query("SELECT id, username, role, unit_id, status_aktif FROM users WHERE role = 'admin_unit'")->getResultArray();
            echo "<h2>Admin Unit Users:</h2>";
            echo "<pre>" . print_r($users, true) . "</pre>";

            // Check indikator
            $indikator = $db->query("SELECT * FROM indikator WHERE status_aktif = 1")->getResultArray();
            echo "<h2>Indikator Aktif:</h2>";
            echo "<pre>" . print_r($indikator, true) . "</pre>";
        } catch (\Exception $e) {
            echo "<h2>❌ Database Error:</h2>";
            echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
        }
    }
}
