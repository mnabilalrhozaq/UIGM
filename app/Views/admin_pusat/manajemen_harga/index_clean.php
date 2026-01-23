<?php
// Helper functions untuk icon
if (!function_exists('getJenisIcon')) {
    function getJenisIcon($jenis) {
        $icons = [
            'Plastik' => 'wine-bottle', 'Kertas' => 'file-alt', 'Logam' => 'cog',
            'Organik' => 'seedling', 'Residu' => 'trash-alt', 'Elektronik' => 'laptop',
            'Anorganik' => 'box', 'Besi' => 'wrench'
        ];
        return $icons[$jenis] ?? 'recycle';
    }
}

if (!function_exists('getActionIcon')) {
    function getActionIcon($action) {
        $icons = ['create' => 'plus-circle', 'update' => 'edit', 'delete' => 'trash-alt'];
        return $icons[$action] ?? 'history';
    }
}

if (!function_exists('getActionColor')) {
    function getActionColor($action) {
        $colors = ['create' => 'success', 'update' => 'primary', 'delete' => 'danger'];
        return $colors[$action] ?? 'secondary';
    }
}

if (!function_exists('getActionText')) {
    function getActionText($action) {
        $texts = ['create' => 'Menambahkan', 'update' => 'Mengupdate', 'delete' => 'Menghapus'];
        return $texts[$action] ?? 'Mengubah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= esc($title ?? 'Manajemen Sampah') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('/css/mobile-responsive.css') ?>" rel="stylesheet">
