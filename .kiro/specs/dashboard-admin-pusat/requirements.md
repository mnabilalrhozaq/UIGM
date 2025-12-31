# Requirements Document

## Introduction

Dashboard Admin Pusat UIGM POLBAN adalah sistem manajemen terpusat yang berfungsi sebagai penerima, reviewer, validator, dan finalisator data dari Admin Unit untuk 6 kategori UIGM (UI GreenMetric). Sistem ini dirancang untuk memastikan kualitas data, konsistensi penilaian, dan efisiensi proses review antar unit di lingkungan Politeknik Negeri Bandung.

## Glossary

- **Admin_Pusat**: Pengguna dengan hak akses untuk mereview, memvalidasi, dan memfinalisasi data dari semua unit
- **Admin_Unit**: Pengguna dari masing-masing unit/fakultas yang menginput data UIGM
- **UIGM_Category**: Enam kategori penilaian UI GreenMetric (Setting & Infrastructure, Energy & Climate Change, Waste, Water, Transportation, Education & Research)
- **Submission**: Data yang dikirim oleh Admin Unit untuk direview
- **Review_Status**: Status review per kategori (Pending, Disetujui, Perlu Revisi)
- **Unit_Status**: Status keseluruhan unit (Draft, Dikirim, Review, Perlu Revisi, Disetujui, Final)
- **Dashboard_System**: Sistem dashboard terintegrasi dengan konsistensi visual dan fungsional

## Requirements

### Requirement 1: Dashboard Interface Management

**User Story:** Sebagai Admin Pusat, saya ingin mengakses dashboard terpusat dengan tampilan yang konsisten dengan Admin Unit, sehingga saya dapat mengelola semua data UIGM dengan efisien.

#### Acceptance Criteria

1. THE Dashboard_System SHALL display header dengan judul "Dashboard Admin Pusat UIGM"
2. WHEN Admin Pusat mengakses dashboard, THE Dashboard_System SHALL menampilkan filter untuk Tahun Penilaian, Unit, dan Status Data
3. THE Dashboard_System SHALL menggunakan warna utama #5c8cbf konsisten dengan Admin Unit
4. THE Dashboard_System SHALL menampilkan ikon notifikasi untuk data baru masuk
5. THE Dashboard_System SHALL menampilkan profil Admin Pusat di header

### Requirement 2: Data Reception and Statistics

**User Story:** Sebagai Admin Pusat, saya ingin melihat ringkasan statistik data masuk dari semua unit, sehingga saya dapat memantau progress keseluruhan institusi.

#### Acceptance Criteria

1. THE Dashboard_System SHALL menampilkan card statistik Total Unit
2. THE Dashboard_System SHALL menampilkan card statistik Unit Sudah Kirim
3. THE Dashboard_System SHALL menampilkan card statistik Unit Belum Kirim
4. THE Dashboard_System SHALL menampilkan card statistik Data Menunggu Review
5. THE Dashboard_System SHALL menampilkan progress bar keseluruhan institusi
6. WHEN data unit berubah, THE Dashboard_System SHALL memperbarui statistik secara real-time

### Requirement 3: Unit Data Management

**User Story:** Sebagai Admin Pusat, saya ingin melihat tabel data masuk dari semua Admin Unit dengan informasi lengkap, sehingga saya dapat mengidentifikasi unit mana yang perlu direview.

#### Acceptance Criteria

1. THE Dashboard_System SHALL menampilkan tabel dengan kolom Nama Unit, Tahun, Progress (%) 6 Kategori, Status Pengiriman, Tanggal Kirim, dan Aksi
2. WHEN menampilkan progress 6 kategori, THE Dashboard_System SHALL menggunakan indikator visual untuk setiap kategori UIGM
3. THE Dashboard_System SHALL menampilkan badge status konsisten (Draft, Dikirim, Perlu Revisi, Disetujui)
4. THE Dashboard_System SHALL menyediakan tombol Review untuk setiap unit yang sudah mengirim data
5. THE Dashboard_System SHALL mengurutkan data berdasarkan tanggal kirim terbaru

### Requirement 4: Review System Implementation

**User Story:** Sebagai Admin Pusat, saya ingin mengakses halaman review detail untuk setiap unit, sehingga saya dapat mereview data dari 6 kategori UIGM secara menyeluruh.

#### Acceptance Criteria

1. WHEN Admin Pusat mengklik tombol Review, THE Dashboard_System SHALL menampilkan halaman detail unit
2. THE Dashboard_System SHALL menampilkan ringkasan unit dengan nama, status data, dan progress 6 kategori
3. THE Dashboard_System SHALL menyediakan tombol "Setujui Semua" dan "Kembalikan untuk Revisi"
4. THE Dashboard_System SHALL menampilkan 6 kategori UIGM sesuai urutan: Setting & Infrastructure, Energy & Climate Change, Waste, Water, Transportation, Education & Research
5. FOR EACH kategori, THE Dashboard_System SHALL menampilkan data input dari Admin Unit dalam mode read-only
6. FOR EACH kategori, THE Dashboard_System SHALL menampilkan dokumen pendukung yang diupload Admin Unit
7. FOR EACH kategori, THE Dashboard_System SHALL menampilkan catatan dari Admin Unit
8. FOR EACH kategori, THE Dashboard_System SHALL menyediakan kolom input untuk Catatan Admin Pusat
9. FOR EACH kategori, THE Dashboard_System SHALL menyediakan pilihan status: Disetujui atau Perlu Revisi

### Requirement 5: Revision Workflow Management

**User Story:** Sebagai Admin Pusat, saya ingin dapat menandai kategori tertentu untuk revisi dengan catatan spesifik, sehingga Admin Unit dapat memperbaiki data sesuai feedback yang diberikan.

#### Acceptance Criteria

1. WHEN Admin Pusat menandai kategori sebagai "Perlu Revisi", THE Dashboard_System SHALL menyimpan catatan revisi
2. WHEN Admin Pusat menyimpan review, THE Dashboard_System SHALL mengubah status unit menjadi "Perlu Revisi" jika ada kategori yang perlu revisi
3. THE Dashboard_System SHALL mengirim notifikasi otomatis ke Admin Unit terkait
4. THE Dashboard_System SHALL membuka kembali hanya kategori yang perlu revisi untuk Admin Unit
5. WHEN Admin Unit mengirim ulang data revisi, THE Dashboard_System SHALL memperbarui status menjadi "Review" dan mengirim notifikasi ke Admin Pusat

### Requirement 6: Data Finalization and Locking

**User Story:** Sebagai Admin Pusat, saya ingin dapat memfinalisasi data unit yang sudah disetujui semua kategorinya, sehingga data menjadi terkunci dan masuk ke rekap institusi.

#### Acceptance Criteria

1. WHEN semua 6 kategori disetujui, THE Dashboard_System SHALL mengubah status unit menjadi "Disetujui"
2. WHEN status unit menjadi "Disetujui", THE Dashboard_System SHALL mengunci data menjadi read-only untuk Admin Unit
3. THE Dashboard_System SHALL memasukkan skor unit ke dalam rekap institusi
4. THE Dashboard_System SHALL menyimpan timestamp finalisasi data
5. THE Dashboard_System SHALL mengirim notifikasi konfirmasi ke Admin Unit

### Requirement 7: Monitoring and Reporting

**User Story:** Sebagai Admin Pusat, saya ingin melihat rekap dan monitoring progress semua unit, sehingga saya dapat membuat laporan dan analisis perbandingan antar unit.

#### Acceptance Criteria

1. THE Dashboard_System SHALL menampilkan grafik perbandingan progress antar unit
2. THE Dashboard_System SHALL menampilkan progress kelengkapan 6 kategori secara keseluruhan
3. THE Dashboard_System SHALL menyediakan filter per indikator dan tahun
4. THE Dashboard_System SHALL menampilkan tren progress dari waktu ke waktu
5. THE Dashboard_System SHALL menyediakan export data untuk laporan

### Requirement 8: Notification System

**User Story:** Sebagai Admin Pusat, saya ingin menerima notifikasi real-time tentang aktivitas data dari Admin Unit, sehingga saya dapat merespons dengan cepat.

#### Acceptance Criteria

1. WHEN Admin Unit mengirim data baru, THE Dashboard_System SHALL mengirim notifikasi ke Admin Pusat
2. WHEN Admin Unit menyelesaikan revisi, THE Dashboard_System SHALL mengirim notifikasi ke Admin Pusat
3. WHEN mendekati deadline pengisian, THE Dashboard_System SHALL mengirim notifikasi reminder
4. THE Dashboard_System SHALL menampilkan badge counter notifikasi yang belum dibaca
5. THE Dashboard_System SHALL menyimpan riwayat semua notifikasi

### Requirement 9: Data Synchronization

**User Story:** Sebagai sistem, saya ingin memastikan sinkronisasi data antara Admin Unit dan Admin Pusat berjalan dengan baik, sehingga tidak ada inkonsistensi data.

#### Acceptance Criteria

1. THE Dashboard_System SHALL memastikan struktur 6 kategori identik antara Admin Unit dan Admin Pusat
2. THE Dashboard_System SHALL memastikan field input sama (1:1 mapping) antara kedua sistem
3. THE Dashboard_System SHALL memperbarui status secara real-time
4. THE Dashboard_System SHALL menyinkronkan catatan dua arah (Admin Unit ↔ Admin Pusat)
5. THE Dashboard_System SHALL menyimpan riwayat versi data untuk audit trail

### Requirement 10: User Interface Consistency

**User Story:** Sebagai pengguna sistem, saya ingin tampilan Dashboard Admin Pusat konsisten dengan Admin Unit, sehingga pengalaman pengguna tetap familiar dan intuitif.

#### Acceptance Criteria

1. THE Dashboard_System SHALL menggunakan warna utama #5c8cbf konsisten dengan Admin Unit
2. THE Dashboard_System SHALL menggunakan layout bersih, akademik, dan profesional
3. THE Dashboard_System SHALL menggunakan card dan tabel dengan gaya sama seperti Admin Unit
4. THE Dashboard_System SHALL menggunakan badge status yang konsisten (Draft, Dikirim, Perlu Revisi, Disetujui)
5. THE Dashboard_System SHALL menggunakan tipografi dan spacing yang konsisten

### Requirement 11: Performance and Scalability

**User Story:** Sebagai Admin Pusat, saya ingin sistem dapat menangani data dari banyak unit secara bersamaan dengan performa yang baik, sehingga tidak ada keterlambatan dalam proses review.

#### Acceptance Criteria

1. THE Dashboard_System SHALL memuat halaman dashboard dalam waktu maksimal 3 detik
2. THE Dashboard_System SHALL dapat menangani minimal 50 unit secara bersamaan
3. THE Dashboard_System SHALL menggunakan pagination untuk tabel data yang besar
4. THE Dashboard_System SHALL menggunakan caching untuk data yang sering diakses
5. THE Dashboard_System SHALL mengoptimalkan query database untuk performa terbaik

### Requirement 12: Security and Access Control

**User Story:** Sebagai Admin Pusat, saya ingin sistem memiliki kontrol akses yang tepat, sehingga hanya pengguna yang berwenang dapat mengakses dan memodifikasi data.

#### Acceptance Criteria

1. THE Dashboard_System SHALL memverifikasi hak akses Admin Pusat sebelum menampilkan dashboard
2. THE Dashboard_System SHALL mencatat semua aktivitas review dalam audit log
3. THE Dashboard_System SHALL mengenkripsi data sensitif dalam database
4. THE Dashboard_System SHALL mengimplementasi session timeout untuk keamanan
5. THE Dashboard_System SHALL memvalidasi semua input untuk mencegah injection attacks
