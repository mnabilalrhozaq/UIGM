<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupOldWaste extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'waste:cleanup';
    protected $description = 'Delete waste data that has been approved/rejected for more than 5 minutes';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        try {
            // Delete data yang sudah disetujui/ditolak lebih dari 5 menit
            $fiveMinutesAgo = date('Y-m-d H:i:s', strtotime('-5 minutes'));
            
            $result = $db->table('waste_management')
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->where('action_timestamp IS NOT NULL')
                ->where('action_timestamp <', $fiveMinutesAgo)
                ->delete();
            
            if ($result) {
                CLI::write("✓ Deleted {$result} old waste records", 'green');
                log_message('info', "Cleanup: Deleted {$result} old waste records");
            } else {
                CLI::write("No old waste records to delete", 'yellow');
            }
            
        } catch (\Exception $e) {
            CLI::error('Error during cleanup: ' . $e->getMessage());
            log_message('error', 'Cleanup Error: ' . $e->getMessage());
        }
    }
}
