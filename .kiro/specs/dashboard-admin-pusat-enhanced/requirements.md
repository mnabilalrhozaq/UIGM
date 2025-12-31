# Requirements Document - Dashboard Admin Pusat Enhanced

## Introduction

Dashboard Admin Pusat Enhanced adalah sistem komprehensif untuk menerima, mereview, memvalidasi, dan memfinalisasi data UIGM dari Admin Unit. Sistem ini dirancang untuk memberikan kontrol penuh kepada Admin Pusat dalam mengelola proses penilaian UI GreenMetric dengan alur kerja yang jelas dan berkesinambungan.

## Glossary

- **Admin_Pusat**: Pengguna dengan role admin pusat yang bertugas mereview dan memvalidasi data
- **Admin_Unit**: Pengguna dengan role admin unit yang mengirim data untuk direview
- **UIGM_Category**: 6 kategori UI GreenMetric (SI, EC, WS, WR, TR, ED)
- **Review_Status**: Status review per kategori (pending, disetujui, perlu_revisi)
- **Submission_Status**: Status pengiriman keseluruhan (draft, dikirim, review, perlu_revisi, disetujui)
- **Dashboard_Admin_Pusat**: Interface utama untuk Admin Pusat mengelola semua data masuk

## Requirements

### Requirement 1: Dashboard Header dan Filter

**User Story:** Sebagai Admin Pusat, saya ingin memiliki header dashboard yang informatif dengan filter yang memudahkan navigasi, sehingga saya dapat dengan cepat mengakses data yang relevan.

#### Acceptance Criteria

1. THE Dashboard_Admin_Pusat SHALL display header dengan judul "Dashboard Admin Pusat UIGM"
2. WHEN Admin_Pusat mengakses dashboard, THE system SHALL provide filter untuk tahun penilaian
3. WHEN Admin_Pusat mengakses dashboard, THE system SHALL provide filter untuk unit tertentu
4. WHEN Admin_Pusat mengakses dashboard, THE system SHALL provide filter untuk status data
5. THE Dashboard_Admin_Pusat SHALL display ikon notifikasi untuk data baru masuk
6. THE Dashboard_Admin_Pusat SHALL display profil Admin_Pusat dengan informasi lengkap

### Requirement 2: Ringkasan Statistik Data Masuk

**User Story:** Sebagai Admin Pusat, saya ingin melihat ringkasan statistik data masuk dalam bentuk card yang jelas, sehingga saya dapat memahami status keseluruhan dengan cepat.

#### Acceptance Criteria

1. THE Dashboard_Admin_Pusat SHALL display total unit yang terdaftar
2. THE Dashboard_Admin_Pusat SHALL display jumlah unit yang sudah mengirim data
3. THE Dashboard_Admin_Pusat SHALL display jumlah unit yang belum mengirim data
4. THE Dashboard_Admin_Pusat SHALL display jumlah data yang menunggu review
5. THE Dashboard_Admin_Pusat SHALL display progress bar keseluruhan institusi
6. WHEN data berubah, THE statistik SHALL update secara real-time

### Requirement 3: Tabel Data Masuk Komprehensif

**User Story:** Sebagai Admin Pusat, saya ingin melihat tabel data masuk yang menampilkan semua informasi penting dari setiap unit, sehingga saya dapat dengan mudah mengidentifikasi unit mana yang perlu direview.

#### Acceptance Criteria

1. THE Dashboard_Admin_Pusat SHALL display tabel dengan kolom nama unit
2. THE Dashboard_Admin_Pusat SHALL display kolom tahun penilaian
3. THE Dashboard_Admin_Pusat SHALL display progress 6 kategori UIGM per unit
4. THE Dashboard_Admin_Pusat SHALL display status pengiriman dengan badge konsisten
5. THE Dashboard_Admin_Pusat SHALL display tanggal kirim data
6. THE Dashboard_Admin_Pusat SHALL provide tombol "Review" untuk setiap unit
7. WHEN Admin_Pusat mengklik tombol Review, THE system SHALL redirect ke halaman detail review

### Requirement 4: Halaman Review Detail Unit

**User Story:** Sebagai Admin Pusat, saya ingin halaman review detail yang menampilkan semua data unit secara komprehensif, sehingga saya dapat melakukan review menyeluruh sebelum memberikan keputusan.

#### Acceptance Criteria

1. THE Review_Detail_Page SHALL display ringkasan unit (nama, status, progress)
2. THE Review_Detail_Page SHALL provide tombol "Setujui Semua" dan "Kembalikan untuk Revisi"
3. THE Review_Detail_Page SHALL display 6 kategori UIGM sesuai urutan yang konsisten
4. FOR EACH UIGM_Category, THE system SHALL display data input dari Admin_Unit dalam mode read-only
5. FOR EACH UIGM_Category, THE system SHALL display dokumen pendukung yang diupload
6. FOR EACH UIGM_Category, THE system SHALL display catatan dari Admin_Unit
7. FOR EACH UIGM_Category, THE system SHALL provide kolom catatan untuk Admin_Pusat
8. FOR EACH UIGM_Category, THE system SHALL provide pilihan status: Disetujui atau Perlu Revisi

### Requirement 5: Sistem Review Per Kategori

**User Story:** Sebagai Admin Pusat, saya ingin dapat mereview setiap kategori UIGM secara individual dengan memberikan catatan spesifik, sehingga Admin Unit dapat memahami dengan jelas apa yang perlu diperbaiki.

#### Acceptance Criteria

1. WHEN Admin_Pusat mereview kategori, THE system SHALL allow input catatan review
2. WHEN Admin_Pusat mengubah status kategori, THE system SHALL save perubahan secara otomatis
3. WHEN Admin_Pusat memberikan status "Perlu Revisi", THE system SHALL require catatan review
4. THE system SHALL track reviewer_id dan tanggal_review untuk setiap kategori
5. WHEN semua kategori direview, THE system SHALL update status pengiriman keseluruhan
6. THE system SHALL provide validasi bahwa semua kategori sudah direview sebelum finalisasi

### Requirement 6: Mekanisme Revisi Berkesinambungan

**User Story:** Sebagai Admin Pusat, saya ingin sistem revisi yang memungkinkan Admin Unit memperbaiki hanya kategori tertentu yang perlu revisi, sehingga proses review menjadi efisien dan tidak mengulang kategori yang sudah disetujui.

#### Acceptance Criteria

1. WHEN Admin_Pusat menandai kategori "Perlu Revisi", THE system SHALL update status pengiriman menjadi "perlu_revisi"
2. WHEN status berubah menjadi "perlu_revisi", THE system SHALL send notifikasi ke Admin_Unit
3. WHEN Admin_Unit memperbaiki kategori yang direvisi, THE system SHALL allow re-submission
4. THE system SHALL maintain status "Disetujui" untuk kategori yang tidak perlu revisi
5. WHEN Admin_Unit mengirim ulang, THE system SHALL update hanya kategori yang direvisi
6. THE system SHALL track riwayat revisi untuk audit trail

### Requirement 7: Finalisasi dan Penguncian Data

**User Story:** Sebagai Admin Pusat, saya ingin dapat memfinalisasi data yang sudah disetujui semua kategorinya, sehingga data menjadi final dan tidak dapat diubah lagi oleh Admin Unit.

#### Acceptance Criteria

1. WHEN semua kategori berstatus "Disetujui", THE system SHALL enable finalisasi
2. WHEN Admin_Pusat melakukan finalisasi, THE system SHALL update status menjadi "disetujui"
3. WHEN data difinalisasi, THE system SHALL make data read-only untuk Admin_Unit
4. WHEN data difinalisasi, THE system SHALL calculate skor final untuk rekap institusi
5. THE system SHALL record tanggal_disetujui dan admin yang memfinalisasi
6. THE system SHALL send notifikasi konfirmasi ke Admin_Unit

### Requirement 8: Rekap dan Monitoring Institusi

**User Story:** Sebagai Admin Pusat, saya ingin dashboard monitoring yang menampilkan rekap dan perbandingan antar unit, sehingga saya dapat melihat gambaran besar performa institusi dalam UI GreenMetric.

#### Acceptance Criteria

1. THE Dashboard_Admin_Pusat SHALL provide grafik perbandingan skor antar unit
2. THE Dashboard_Admin_Pusat SHALL display progress kelengkapan 6 kategori secara keseluruhan
3. THE Dashboard_Admin_Pusat SHALL provide filter per indikator dan tahun
4. THE Dashboard_Admin_Pusat SHALL display ranking unit berdasarkan skor
5. THE Dashboard_Admin_Pusat SHALL provide export data untuk laporan
6. THE Dashboard_Admin_Pusat SHALL display trend progress dari waktu ke waktu

### Requirement 9: Sistem Notifikasi Komprehensif

**User Story:** Sebagai Admin Pusat, saya ingin sistem notifikasi yang memberitahu saya tentang semua aktivitas penting, sehingga saya tidak melewatkan data yang perlu direview atau deadline yang penting.

#### Acceptance Criteria

1. WHEN Admin_Unit mengirim data baru, THE system SHALL send notifikasi ke Admin_Pusat
2. WHEN Admin_Unit mengirim ulang revisi, THE system SHALL send notifikasi update
3. WHEN mendekati deadline, THE system SHALL send reminder notification
4. THE system SHALL display badge counter untuk notifikasi yang belum dibaca
5. THE system SHALL provide halaman notifikasi dengan filter dan pencarian
6. THE system SHALL allow Admin_Pusat untuk mark notifikasi sebagai read/unread

### Requirement 10: Sinkronisasi Data Real-time

**User Story:** Sebagai Admin Pusat, saya ingin data yang saya lihat selalu sinkron dengan data terbaru dari Admin Unit, sehingga keputusan review yang saya buat berdasarkan informasi yang akurat dan terkini.

#### Acceptance Criteria

1. THE system SHALL ensure struktur 6 kategori identik antara Admin_Unit dan Admin_Pusat
2. THE system SHALL maintain field input yang sama (1:1 mapping)
3. THE system SHALL update status secara real-time ketika ada perubahan
4. THE system SHALL support catatan dua arah (Admin_Unit ↔ Admin_Pusat)
5. THE system SHALL maintain riwayat versi data untuk audit trail
6. THE system SHALL prevent data corruption dengan proper transaction handling

### Requirement 11: Interface Konsisten dan User Experience

**User Story:** Sebagai Admin Pusat, saya ingin interface yang konsisten dengan dashboard Admin Unit dan mudah digunakan, sehingga saya dapat bekerja dengan efisien tanpa kebingungan navigasi.

#### Acceptance Criteria

1. THE Dashboard_Admin_Pusat SHALL use warna utama #5c8cbf secara konsisten
2. THE Dashboard_Admin_Pusat SHALL maintain layout bersih dan profesional
3. THE Dashboard_Admin_Pusat SHALL use card dan tabel dengan gaya yang sama seperti Admin Unit
4. THE Dashboard_Admin_Pusat SHALL use badge status yang konsisten (Draft, Dikirim, Perlu Revisi, Disetujui)
5. THE Dashboard_Admin_Pusat SHALL be responsive untuk berbagai ukuran layar
6. THE Dashboard_Admin_Pusat SHALL provide loading states dan feedback yang jelas

### Requirement 12: Keamanan dan Kontrol Akses

**User Story:** Sebagai Admin Pusat, saya ingin sistem yang aman dan hanya memberikan akses sesuai dengan role saya, sehingga integritas data dan proses review terjaga.

#### Acceptance Criteria

1. THE system SHALL validate bahwa hanya Admin_Pusat yang dapat mengakses dashboard
2. THE system SHALL log semua aktivitas review untuk audit trail
3. THE system SHALL prevent unauthorized access ke data unit lain
4. THE system SHALL maintain session security dengan timeout yang appropriate
5. THE system SHALL validate semua input untuk mencegah injection attacks
6. THE system SHALL backup data secara otomatis sebelum finalisasi
