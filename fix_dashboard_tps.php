<?php
/**
 * Script untuk memperbaiki struktur dashboard TPS
 * Menghapus duplikasi dan merapikan struktur HTML
 */

$file = 'app/Views/pengelola_tps/dashboard.php';
$content = file_get_contents($file);

// Backup original file
file_put_contents($file . '.backup', $content);

// Hapus bagian yang duplikat (line 238-610)
// Cari posisi closing tags pertama yang salah
$pattern1 = '/\s*<\/div>\s*<script src="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap@5\.1\.3\/dist\/js\/bootstrap\.bundle\.min\.js"><\/script>\s*<!-- Mobile Menu JS -->\s*<script src="<\?= base_url\(\'\/js\/mobile-menu\.js\'\) \?>"><\/script>\s*<\/body>\s*<\/html>\s*<\?php.*?function getWasteIcon.*?\?>\s*<style>.*?<\/style>/s';

// Ganti dengan closing tags yang benar saja
$replacement1 = '
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Mobile Menu JS -->
    <script src="<?= base_url(\'/js/mobile-menu.js\') ?>"></script>
</body>
</html>';

$newContent = preg_replace($pattern1, $replacement1, $content);

if ($newContent === null) {
    echo "Error: Regex replacement failed\n";
    echo "Trying alternative method...\n";
    
    // Method 2: Find and remove specific sections
    // Find position of first </div> before scripts
    $pos1 = strpos($content, '    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>');
    
    if ($pos1 !== false) {
        // Find position of </style> at the end
        $pos2 = strrpos($content, '</style>');
        
        if ($pos2 !== false) {
            // Keep everything before pos1
            $part1 = substr($content, 0, $pos1);
            
            // Add proper closing
            $part2 = '    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Mobile Menu JS -->
    <script src="<?= base_url(\'/js/mobile-menu.js\') ?>"></script>
</body>
</html>';
            
            $newContent = $part1 . $part2;
            
            echo "Alternative method successful!\n";
        }
    }
}

if ($newContent && $newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "✅ Dashboard TPS berhasil diperbaiki!\n";
    echo "📁 Backup disimpan di: {$file}.backup\n";
    echo "📊 Ukuran sebelum: " . strlen($content) . " bytes\n";
    echo "📊 Ukuran sesudah: " . strlen($newContent) . " bytes\n";
    echo "📉 Dikurangi: " . (strlen($content) - strlen($newContent)) . " bytes\n";
} else {
    echo "❌ Tidak ada perubahan atau terjadi error\n";
}
