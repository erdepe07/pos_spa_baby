<?php
declare(strict_types=1);
$configFile = __DIR__ . '/config.php';
if (is_file(__DIR__ . '/config.local.php')) $configFile = __DIR__ . '/config.local.php';
$config = require $configFile;
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$result = ['PHP' => PHP_VERSION, 'File konfigurasi' => basename($configFile), 'PDO MySQL' => in_array('mysql', PDO::getAvailableDrivers(), true) ? 'Tersedia' : 'Tidak tersedia'];
$tables = [];
try {
    if (!in_array('mysql', PDO::getAvailableDrivers(), true)) throw new RuntimeException('Ekstensi PDO MySQL tidak aktif pada PHP hosting.');
    $pdo = new PDO('mysql:host='.$config['host'].';port='.$config['port'].';dbname='.$config['database'].';charset=utf8mb4', $config['username'], $config['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    $result['Koneksi database'] = 'Berhasil';
    $result['Database aktif'] = (string)$pdo->query('SELECT DATABASE()')->fetchColumn();
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $result['Jumlah tabel terlihat'] = (string)count($tables);
    $required = ['users','permissions','customers','members','treatments','visits'];
    $result['Tabel aplikasi utama'] = count(array_intersect($required, $tables)).' dari '.count($required);
} catch (Throwable $error) {
    $result['Koneksi / pemeriksaan'] = get_class($error).': '.$error->getMessage();
}
?><!doctype html><html lang="id"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pemeriksaan Baby Spa</title><style>body{font:16px Arial,sans-serif;max-width:760px;margin:40px auto;padding:0 18px;color:#263a35}h1{font-size:24px}table{border-collapse:collapse;width:100%}th,td{text-align:left;padding:10px;border-bottom:1px solid #ddd}th{width:230px}aside{margin-top:22px;padding:12px;background:#fff4d8}</style><h1>Pemeriksaan koneksi Baby Spa</h1><table><?php foreach($result as $key=>$value): ?><tr><th><?=h($key)?></th><td><?=h($value)?></td></tr><?php endforeach ?></table><?php if($tables): ?><h2>Tabel database</h2><p><?=h(implode(', ', $tables))?></p><?php endif ?><aside>Halaman ini bersifat sementara. Hapus file <strong>diagnose.php</strong> dari htdocs setelah hasilnya dicatat.</aside></html>
