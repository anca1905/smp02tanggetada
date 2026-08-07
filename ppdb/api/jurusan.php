<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    // Menggunakan tabel majors_content yang kita buat khusus untuk konten website
    $stmt = $pdo->query('SELECT * FROM majors_content ORDER BY id ASC');
    $majors = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => $majors,
    ]);
} catch (PDOException $e) {
    // Fallback if table doesn't exist or DB error
    $majors = [
        ['code' => 'TKJ', 'name' => 'Teknik Komputer & Jaringan', 'image_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1000&auto=format&fit=crop', 'description' => 'Mempelajari jaringan, server, dan mikrotik.'],
        ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak', 'image_url' => 'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?q=80&w=1000&auto=format&fit=crop', 'description' => 'Fokus pada coding, pembuatan website dan aplikasi.'],
        ['code' => 'TBSM', 'name' => 'Teknik & Bisnis Sepeda Motor', 'image_url' => 'https://images.unsplash.com/photo-1552656967-7a0990a02302?q=80&w=1000&auto=format&fit=crop', 'description' => 'Keahlian mesin dan kelistrikan sepeda motor.'],
        ['code' => 'AKL', 'name' => 'Akuntansi Keuangan Lembaga', 'image_url' => 'https://images.unsplash.com/photo-1554224155-984067586967?q=80&w=1000&auto=format&fit=crop', 'description' => 'Mempelajari siklus akuntansi dan keuangan.'],
    ];

    echo json_encode([
        'status' => 'success',
        'data' => $majors,
        'message' => 'Serving fallback data (DB error: '.$e->getMessage().')',
    ]);
}
