<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../config/database.php';

try {
    $stmt = $pdo->query("SELECT nama_sekolah, slogan, deskripsi, email, telepon, alamat, maps_embed, facebook, instagram, youtube, mode_maintenance, buka_ppdb, file_brosur FROM pengaturan LIMIT 1");
    $data = $stmt->fetch();

    if ($data) {
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Settings not found']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database Error']);
}
?>
