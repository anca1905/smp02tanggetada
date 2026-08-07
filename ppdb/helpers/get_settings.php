<?php

// helpers/get_settings.php

$host = 'localhost';
$dbname = 'db_ppdb';
$username = 'root';
$password = '';

try {
    $pdo_settings = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_settings->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_settings->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $pdo_settings->query('SELECT * FROM pengaturan LIMIT 1');
    $settings = $stmt->fetch();
} catch (PDOException $e) {
    // If database connection fails, define default empty settings or handle error
    $settings = [];
    error_log('Database Error in get_settings.php: '.$e->getMessage());
}
