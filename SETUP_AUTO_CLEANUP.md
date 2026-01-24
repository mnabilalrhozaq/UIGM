# Setup Auto Cleanup untuk Waste Data

Setelah admin approve/reject data waste, data akan tetap tampil di dashboard selama 5 menit, kemudian otomatis dihapus.

## Cara Kerja

1. Saat admin approve/reject data, sistem akan:
   - Update status menjadi `disetujui` atau `ditolak`
   - Set `action_timestamp` dengan waktu sekarang
   - Insert data ke tabel `laporan_waste`

2. Data akan tetap tampil di dashboard selama 5 menit

3. Setelah 5 menit, data otomatis dihapus dari `waste_management`

## Setup Cron Job (Production)

### Windows (Task Scheduler)

1. Buka Task Scheduler
2. Create Basic Task
3. Name: "Cleanup Old Waste Data"
4. Trigger: Daily, repeat every 1 minute
5. Action: Start a program
   - Program: `php`
   - Arguments: `F:\laragon\www\eksperimen\spark waste:cleanup`
   - Start in: `F:\laragon\www\eksperimen`

### Linux/Mac (Crontab)

```bash
# Edit crontab
crontab -e

# Add this line (runs every minute)
* * * * * cd /path/to/eksperimen && php spark waste:cleanup >> /dev/null 2>&1
```

## Manual Cleanup (Testing)

Untuk testing, jalankan command ini secara manual:

```bash
php spark waste:cleanup
```

## Alternatif: Auto Cleanup via Web Request

Jika tidak bisa setup cron job, bisa panggil cleanup via web request setiap kali dashboard diakses:

Edit `app/Controllers/Admin/Dashboard.php`:

```php
public function index()
{
    // Auto cleanup old waste data
    $this->cleanupOldWaste();
    
    // ... rest of code
}

private function cleanupOldWaste()
{
    $db = \Config\Database::connect();
    $fiveMinutesAgo = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    
    $db->table('waste_management')
        ->whereIn('status', ['disetujui', 'ditolak'])
        ->where('action_timestamp IS NOT NULL')
        ->where('action_timestamp <', $fiveMinutesAgo)
        ->delete();
}
```

## Verifikasi

Untuk memastikan cleanup berjalan:

1. Admin approve/reject data
2. Data masih tampil di dashboard
3. Tunggu 5 menit
4. Refresh dashboard
5. Data sudah hilang

## Troubleshooting

Jika data tidak hilang setelah 5 menit:

1. Cek apakah cron job berjalan: `php spark waste:cleanup`
2. Cek log: `writable/logs/log-*.log`
3. Cek kolom `action_timestamp` di database
