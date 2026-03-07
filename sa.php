<?php
// Diagnostic Tool Sederhana
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "===== DIAGNOSTIC TOOL =====\n\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script Path: " . __FILE__ . "\n";
echo "Current Time: " . date('Y-m-d H:i:s') . "\n\n";

echo "Mencoba include v2.php...\n";

if (file_exists('v2.php')) {
    echo "File v2.php ditemukan!\n";
    echo "Ukuran: " . filesize('v2.php') . " bytes\n";
    
    // Coba include
    try {
        include 'v2.php';
        echo "SUKSES: File berhasil di-include!\n";
    } catch (Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "ERROR: File v2.php TIDAK ditemukan di direktori ini!\n";
    echo "Direktori saat ini: " . __DIR__ . "\n";
    echo "Isi direktori:\n";
    $files = scandir(__DIR__);
    foreach ($files as $file) {
        echo "  - $file\n";
    }
}
?>
