<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    // Ambil berita terbaru dengan status Published
    $stmt = $pdo->query("SELECT * FROM berita WHERE status = 'Published' ORDER BY tanggal DESC LIMIT 5");
    $news = $stmt->fetchAll();

    // Mapping field agar sesuai dengan yang diharapkan frontend (atau ubah frontend)
    // Di sini kita kirim raw data saja, nanti frontend menyesuaikan
    echo json_encode([
        'status' => 'success',
        'data' => $news
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil berita: ' . $e->getMessage()
    ]);
}
?>
