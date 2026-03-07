<?php
/**
 * Error Reporting Tool - Web Version
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error Diagnostic Tool</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #2d2d30;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            border: 1px solid #0ff;
        }
        h1 {
            color: #0ff;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #0ff;
        }
        h2 {
            color: #f60;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        .success {
            color: #0f0;
        }
        .error {
            color: #f44;
        }
        .warning {
            color: #ff0;
        }
        .info {
            color: #0ff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #333;
        }
        .label {
            color: #f60;
            width: 200px;
        }
        .value {
            color: #ff0;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #333;
            overflow-x: auto;
            color: #0f0;
        }
        .button {
            background: transparent;
            border: 1px solid #0ff;
            color: #0ff;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            border-radius: 5px;
        }
        .button:hover {
            background: #0ff;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 ERROR DIAGNOSTIC TOOL</h1>
        
        <?php
        function checkStatus($condition, $message) {
            if ($condition) {
                return "<span class='success'>✅</span>";
            } else {
                return "<span class='error'>❌</span>";
            }
        }
        
        $targetFile = __DIR__ . '/v2.php';
        $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
        ?>

        <!-- Informasi Dasar -->
        <h2>📌 Basic Information</h2>
        <table>
            <tr><td class="label">Time:</td><td class="value"><?php echo date('Y-m-d H:i:s'); ?></td></tr>
            <tr><td class="label">PHP Version:</td><td class="value"><?php echo phpversion(); ?></td></tr>
            <tr><td class="label">Server API:</td><td class="value"><?php echo php_sapi_name(); ?></td></tr>
            <tr><td class="label">Document Root:</td><td class="value"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td></tr>
            <tr><td class="label">Script Path:</td><td class="value"><?php echo __FILE__; ?></td></tr>
            <tr><td class="label">Current Directory:</td><td class="value"><?php echo getcwd(); ?></td></tr>
        </table>

        <!-- File Check -->
        <h2>📁 File Check (v2.php)</h2>
        <table>
            <?php if (file_exists($targetFile)): ?>
                <tr><td class="label">Status:</td><td class="value"><?php echo checkStatus(true, ''); ?> File ditemukan</td></tr>
                <tr><td class="label">Size:</td><td class="value"><?php echo filesize($targetFile); ?> bytes</td></tr>
                <tr><td class="label">Modified:</td><td class="value"><?php echo date('Y-m-d H:i:s', filemtime($targetFile)); ?></td></tr>
                <tr><td class="label">Readable:</td><td class="value"><?php echo checkStatus(is_readable($targetFile), ''); ?> <?php echo is_readable($targetFile) ? 'Yes' : 'No'; ?></td></tr>
            <?php else: ?>
                <tr><td class="label" colspan="2" class="error">❌ File v2.php TIDAK ditemukan!</td></tr>
            <?php endif; ?>
        </table>

        <!-- Available Functions -->
        <h2>⚙️ Available Functions</h2>
        <table>
            <?php
            $functions = ['shell_exec', 'exec', 'system', 'passthru', 'popen', 'proc_open', 'curl_init'];
            foreach ($functions as $func):
                $available = function_exists($func) && !in_array($func, $disabled);
            ?>
                <tr>
                    <td class="label"><?php echo $func; ?>():</td>
                    <td class="value">
                        <?php echo checkStatus($available, ''); ?>
                        <?php echo $available ? 'Available' : 'Not Available'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- PHP Configuration -->
        <h2>🔧 PHP Configuration</h2>
        <table>
            <tr><td class="label">allow_url_fopen:</td><td class="value"><?php echo checkStatus(ini_get('allow_url_fopen'), ''); ?> <?php echo ini_get('allow_url_fopen'); ?></td></tr>
            <tr><td class="label">file_uploads:</td><td class="value"><?php echo checkStatus(ini_get('file_uploads'), ''); ?> <?php echo ini_get('file_uploads'); ?></td></tr>
            <tr><td class="label">upload_max_filesize:</td><td class="value"><?php echo ini_get('upload_max_filesize'); ?></td></tr>
            <tr><td class="label">post_max_size:</td><td class="value"><?php echo ini_get('post_max_size'); ?></td></tr>
            <tr><td class="label">max_execution_time:</td><td class="value"><?php echo ini_get('max_execution_time'); ?> seconds</td></tr>
            <tr><td class="label">memory_limit:</td><td class="value"><?php echo ini_get('memory_limit'); ?></td></tr>
            <tr><td class="label">disable_functions:</td><td class="value"><?php echo ini_get('disable_functions') ?: 'none'; ?></td></tr>
        </table>

        <!-- Session Check -->
        <h2>🍪 Session Check</h2>
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        ?>
        <table>
            <tr><td class="label">Session Status:</td><td class="value"><?php echo checkStatus(isset($_SESSION), ''); ?> <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive'; ?></td></tr>
            <tr><td class="label">Session Save Path:</td><td class="value"><?php echo session_save_path() ?: 'default'; ?></td></tr>
        </table>

        <!-- Permission Check -->
        <h2>📂 Permission Check</h2>
        <table>
            <?php
            $testFile = __DIR__ . '/test_write.tmp';
            $canWrite = @file_put_contents($testFile, 'test') !== false;
            if ($canWrite) unlink($testFile);
            
            $canCreateDir = @mkdir(__DIR__ . '/test_dir');
            if ($canCreateDir) rmdir(__DIR__ . '/test_dir');
            ?>
            <tr><td class="label">Write Permission:</td><td class="value"><?php echo checkStatus($canWrite, ''); ?> <?php echo $canWrite ? 'Yes' : 'No'; ?></td></tr>
            <tr><td class="label">Create Directory:</td><td class="value"><?php echo checkStatus($canCreateDir, ''); ?> <?php echo $canCreateDir ? 'Yes' : 'No'; ?></td></tr>
        </table>

        <!-- Error Log -->
        <h2>📝 Error Log</h2>
        <table>
            <tr><td class="label">log_errors:</td><td class="value"><?php echo checkStatus(ini_get('log_errors'), ''); ?> <?php echo ini_get('log_errors') ? 'On' : 'Off'; ?></td></tr>
            <tr><td class="label">error_log:</td><td class="value"><?php echo ini_get('error_log') ?: 'not set'; ?></td></tr>
        </table>
        <?php
        $logFile = ini_get('error_log');
        if ($logFile && file_exists($logFile) && is_readable($logFile)):
        ?>
            <h3>Last 5 lines from error log:</h3>
            <pre><?php echo htmlspecialchars(implode('', array_slice(file($logFile), -5))); ?></pre>
        <?php endif; ?>

        <!-- Test Include -->
        <h2>🚀 Test Include v2.php</h2>
        <?php if (file_exists($targetFile)): 
            ob_start();
            try {
                include $targetFile;
                $output = ob_get_clean();
                if (empty($output)) {
                    echo "<p class='warning'>⚠️ File di-include tapi tidak menghasilkan output (mungkin redirect ke login?)</p>";
                } else {
                    echo "<p class='success'>✅ File berhasil di-include</p>";
                }
            } catch (Throwable $e) {
                ob_end_clean();
                echo "<p class='error'>❌ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<pre>File: " . $e->getFile() . ":" . $e->getLine() . "</pre>";
            }
        else: ?>
            <p class='error'>❌ File tidak ditemukan, tidak bisa test include</p>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div style="margin-top: 30px; text-align: center;">
            <a href="?refresh=1" class="button">🔄 Refresh</a>
            <a href="v2.php" class="button">🚀 Go to v2.php</a>
            <a href="error_check.php" class="button">📋 This Tool</a>
        </div>

        <div style="margin-top: 20px; color: #666; text-align: center; font-size: 12px;">
            <?php echo "Memory usage: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB"; ?>
        </div>
    </div>
</body>
</html>
