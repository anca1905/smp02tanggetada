<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once '../../config/database.php';

session_start();
if (! isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    // === GET: List All Mapel ===
    if ($method === 'GET') {
        $stmt = $pdo->query('SELECT * FROM mapel ORDER BY kelompok ASC, kode_mapel ASC');
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // === POST: Add, Update, Delete ===
    if ($method === 'POST') {
        if (isset($input['_method']) && $input['_method'] === 'DELETE') {
            if (empty($input['id'])) {
                throw new Exception('ID required');
            }
            $stmt = $pdo->prepare('DELETE FROM mapel WHERE id = ?');
            $stmt->execute([$input['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Deleted']);
            exit;
        }

        if (empty($input['nama_mapel'])) {
            throw new Exception('Nama Mapel wajib diisi');
        }
        if (empty($input['kode_mapel'])) {
            throw new Exception('Kode Mapel wajib diisi');
        }

        $kode = $input['kode_mapel'];
        $nama = $input['nama_mapel'];
        $kelompok = $input['kelompok'] ?? 'A';
        $jurusan = $input['jurusan'] ?? 'Umum';
        $guru = $input['nama_guru'] ?? '';
        $jp = $input['beban_jp'] ?? 2;

        if (! empty($input['id'])) {
            $sql = 'UPDATE mapel SET kode_mapel=?, nama_mapel=?, kelompok=?, jurusan=?, nama_guru=?, beban_jp=? WHERE id=?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode, $nama, $kelompok, $jurusan, $guru, $jp, $input['id']]);
        } else {
            $sql = 'INSERT INTO mapel (kode_mapel, nama_mapel, kelompok, jurusan, nama_guru, beban_jp) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode, $nama, $kelompok, $jurusan, $guru, $jp]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Data saved']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
