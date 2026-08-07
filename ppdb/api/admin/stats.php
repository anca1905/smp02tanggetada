<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../config/database.php';

try {
    // Count Pendaftar
    $stmt = $pdo->query('SELECT COUNT(*) FROM ppdb');
    $totalPendaftar = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM ppdb WHERE status_pendaftaran = 'Pending'");
    $pendingPendaftar = $stmt->fetchColumn();

    // Count Berita
    $stmt = $pdo->query('SELECT COUNT(*) FROM berita');
    $totalBerita = $stmt->fetchColumn();

    // Count Guru
    $stmt = $pdo->query('SELECT COUNT(*) FROM guru');
    $totalGuru = $stmt->fetchColumn();

    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_pendaftar' => $totalPendaftar,
            'pending_pendaftar' => $pendingPendaftar,
            'total_berita' => $totalBerita,
            'total_guru' => $totalGuru,
        ],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database Error: '.$e->getMessage()]);
}
