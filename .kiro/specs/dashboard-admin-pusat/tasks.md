# Implementation Plan: Dashboard Admin Pusat UIGM POLBAN

## Overview

Implementation plan untuk Dashboard Admin Pusat yang berfungsi sebagai penerima, reviewer, validator, dan finalisator data dari Admin Unit untuk 6 kategori UIGM. Sistem ini akan dibangun menggunakan CodeIgniter 4 dengan konsistensi visual dan fungsional dengan Admin Unit yang sudah ada.

## Tasks

- [x] 1. Setup Database Schema dan Models

  - Buat migration untuk tabel admin pusat (unit, pengiriman_unit, review_kategori, notifikasi, riwayat_versi)
  - Implementasi Models dengan relationships dan business logic
  - Setup seeder untuk data sample admin pusat
  - _Requirements: 9.1, 9.2, 9.5_

- [ ]\* 1.1 Write property test untuk database schema consistency

  - **Property 17: Category Structure Synchronization**
  - **Validates: Requirements 9.1, 9.2**

- [ ] 2. Implementasi AdminPusat Controller Core

  - [ ] 2.1 Buat AdminPusat controller dengan method index()

    - Implementasi dashboard utama dengan statistik dan data table
    - Integrasi dengan database models untuk data aggregation
    - _Requirements: 1.1, 2.1, 2.2, 2.3, 2.4, 2.5, 3.1_

  - [ ]\* 2.2 Write unit tests untuk dashboard statistics

    - Test statistik cards (Total Unit, Unit Sudah Kirim, Unit Belum Kirim, Data Menunggu Review)
    - Test progress bar calculation
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

  - [ ] 2.3 Implementasi method review() untuk detail review unit

    - Halaman review detail dengan 6 kategori UIGM
    - Display data Admin Unit dalam mode read-only
    - Form input untuk catatan dan status review Admin Pusat
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9_

  - [ ]\* 2.4 Write property test untuk review navigation
    - **Property 6: Navigation Behavior**
    - **Validates: Requirements 4.1**

- [ ] 3. Implementasi Review Workflow System

  - [ ] 3.1 Buat method updateReview() untuk proses review

    - Logic untuk update status per kategori
    - Calculation status unit berdasarkan status kategori
    - Implementasi revision workflow dan finalization
    - _Requirements: 5.1, 5.2, 6.1, 6.2, 6.3, 6.4_

  - [ ]\* 3.2 Write property test untuk unit status calculation

    - **Property 9: Unit Status Calculation**
    - **Validates: Requirements 5.2, 6.1**

  - [ ]\* 3.3 Write property test untuk data locking on approval

    - **Property 12: Data Locking on Approval**
    - **Validates: Requirements 6.2, 6.3**

  - [ ] 3.4 Implementasi selective category access control

    - Logic untuk membuka hanya kategori yang perlu revisi
    - Access control untuk Admin Unit berdasarkan review status
    - _Requirements: 5.4_

  - [ ]\* 3.5 Write property test untuk selective category access
    - **Property 11: Selective Category Access**
    - **Validates: Requirements 5.4**

- [ ] 4. Checkpoint - Ensure core functionality works

  - Ensure all tests pass, ask the user if questions arise.

- [ ] 5. Implementasi Notification System

  - [ ] 5.1 Buat NotificationService class

    - Service untuk generate dan send notifications
    - Integration dengan review workflow
    - Support untuk berbagai tipe notifikasi (data_masuk, revisi_selesai, deadline, approval, rejection)
    - _Requirements: 5.3, 5.5, 6.5, 8.1, 8.2, 8.3_

  - [ ]\* 5.2 Write property test untuk notification generation

    - **Property 10: Notification Generation**
    - **Validates: Requirements 5.3, 5.5, 6.5, 8.1, 8.2**

  - [ ] 5.3 Implementasi notification display dan management

    - Method notifikasi() untuk display notifications
    - Badge counter untuk unread notifications
    - Mark notification as read functionality
    - _Requirements: 8.4, 8.5_

  - [ ]\* 5.4 Write property test untuk notification counter accuracy
    - **Property 15: Notification Counter Accuracy**
    - **Validates: Requirements 8.4**

- [ ] 6. Implementasi User Interface Components

  - [ ] 6.1 Buat layout admin_pusat.php

    - Header dengan title, filters, notification icon, user profile
    - Sidebar navigation konsisten dengan Admin Unit
    - Responsive design dengan warna #5c8cbf
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 10.1, 10.2, 10.3, 10.5_

  - [ ]\* 6.2 Write property test untuk visual consistency

    - **Property 1: Visual Consistency Enforcement**
    - **Validates: Requirements 1.3, 10.1, 10.3, 10.4, 10.5**

  - [ ] 6.3 Buat dashboard view dengan statistics cards

    - Cards untuk Total Unit, Unit Sudah Kirim, Unit Belum Kirim, Data Menunggu Review
    - Progress bar keseluruhan institusi
    - Tabel data masuk dari Admin Unit dengan kolom lengkap
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 3.1, 3.2, 3.3, 3.4, 3.5_

  - [ ]\* 6.4 Write property test untuk status badge consistency

    - **Property 3: Status Badge Consistency**
    - **Validates: Requirements 3.3, 10.4**

  - [ ] 6.5 Buat review detail view

    - Ringkasan unit dengan nama, status, progress 6 kategori
    - Display 6 kategori UIGM sesuai urutan yang benar
    - Form review untuk setiap kategori dengan catatan dan status
    - Tombol "Setujui Semua" dan "Kembalikan untuk Revisi"
    - _Requirements: 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9_

  - [ ]\* 6.6 Write property test untuk category data display
    - **Property 7: Category Data Display Consistency**
    - **Validates: Requirements 4.5, 4.6, 4.7, 4.8, 4.9**

- [ ] 7. Implementasi Monitoring dan Reporting

  - [ ] 7.1 Buat method monitoring() untuk rekap dan analisis

    - Grafik perbandingan progress antar unit
    - Progress kelengkapan 6 kategori secara keseluruhan
    - Filter per indikator dan tahun
    - Tren progress dari waktu ke waktu
    - _Requirements: 7.1, 7.2, 7.3, 7.4_

  - [ ] 7.2 Implementasi export functionality untuk laporan

    - Export data dalam format Excel/CSV
    - Generate PDF reports
    - _Requirements: 7.5_

  - [ ]\* 7.3 Write unit tests untuk monitoring features
    - Test grafik data generation
    - Test export functionality
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 8. Implementasi Real-time Features dan Performance

  - [ ] 8.1 Implementasi real-time data synchronization

    - AJAX updates untuk statistics dan status
    - Real-time notification updates
    - Auto-refresh mechanisms
    - _Requirements: 2.6, 9.3, 9.4_

  - [ ]\* 8.2 Write property test untuk real-time synchronization

    - **Property 2: Real-time Data Synchronization**
    - **Validates: Requirements 2.6, 9.3, 9.4**

  - [ ] 8.3 Implementasi performance optimizations

    - Pagination untuk tabel data besar
    - Database query optimization
    - Caching untuk data yang sering diakses
    - _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5_

  - [ ]\* 8.4 Write property test untuk pagination behavior
    - **Property 19: Pagination Behavior**
    - **Validates: Requirements 11.3**

- [ ] 9. Implementasi Security dan Access Control

  - [ ] 9.1 Implementasi authentication dan authorization

    - Middleware untuk verify Admin Pusat access
    - Session management dengan timeout
    - Access control untuk different user roles
    - _Requirements: 12.1, 12.4_

  - [ ]\* 9.2 Write property test untuk access control

    - **Property 20: Access Control Verification**
    - **Validates: Requirements 12.1**

  - [ ] 9.3 Implementasi input validation dan security

    - Validation untuk semua form inputs
    - Protection against injection attacks
    - Data sanitization dan encryption
    - _Requirements: 12.3, 12.5_

  - [ ]\* 9.4 Write property test untuk input validation

    - **Property 23: Input Validation Protection**
    - **Validates: Requirements 12.5**

  - [ ] 9.5 Implementasi audit logging system

    - Log semua aktivitas review
    - Audit trail untuk data changes
    - Version history tracking
    - _Requirements: 12.2, 9.5_

  - [ ]\* 9.6 Write property test untuk activity logging
    - **Property 21: Activity Logging**
    - **Validates: Requirements 12.2**

- [ ] 10. Integration dan Testing

  - [ ] 10.1 Implementasi integration dengan Admin Unit system

    - API endpoints untuk data synchronization
    - Webhook untuk real-time updates
    - Data consistency checks
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

  - [ ]\* 10.2 Write property test untuk version history tracking

    - **Property 18: Version History Tracking**
    - **Validates: Requirements 9.5**

  - [ ] 10.3 Setup routing dan middleware

    - Routes untuk semua Admin Pusat endpoints
    - Middleware untuk authentication dan authorization
    - Error handling dan exception management
    - _Requirements: 12.1, 12.4_

  - [ ]\* 10.4 Write property test untuk session security
    - **Property 22: Session Security Management**
    - **Validates: Requirements 12.4**

- [ ] 11. Final Testing dan Quality Assurance

  - [ ]\* 11.1 Write comprehensive integration tests

    - Test complete workflow dari Admin Unit ke Admin Pusat
    - Test error scenarios dan edge cases
    - Performance testing untuk load capacity
    - _Requirements: 11.1, 11.2_

  - [ ]\* 11.2 Write property test untuk revision data persistence

    - **Property 8: Revision Data Persistence**
    - **Validates: Requirements 5.1**

  - [ ]\* 11.3 Write property test untuk timestamp recording

    - **Property 13: Timestamp Recording**
    - **Validates: Requirements 6.4**

  - [ ]\* 11.4 Write property test untuk deadline notifications

    - **Property 14: Deadline Notification Behavior**
    - **Validates: Requirements 8.3**

  - [ ]\* 11.5 Write property test untuk notification history
    - **Property 16: Notification History Persistence**
    - **Validates: Requirements 8.5**

- [ ] 12. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties
- Unit tests validate specific examples and edge cases
- Implementation menggunakan CodeIgniter 4 dengan PHP 8.0+
- Database menggunakan MySQL/MariaDB
- Frontend menggunakan Bootstrap 5.3, Chart.js, Font Awesome
- Konsistensi visual dengan warna utama #5c8cbf (POLBAN blue)
