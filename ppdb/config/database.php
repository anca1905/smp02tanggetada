<?php
// Config Database
$host = 'localhost';
$dbname = 'db_ppdb';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set Error Mode ke Exception agar mudah debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set Default Fetch Mode ke Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error (JSON format karena ini API)
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi Database Gagal: ' . $e->getMessage()
    ]);
    exit;
}
?>
