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
    // === GET: List All Registrants ===
    if ($method === 'GET') {
        $sql = 'SELECT * FROM ppdb ORDER BY tanggal_daftar DESC';
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // === POST: Update Status or Delete ===
    if ($method === 'POST') {

        // DELETE
        if (isset($input['_method']) && $input['_method'] === 'DELETE') {
            if (empty($input['id'])) {
                throw new Exception('ID required');
            }
            $stmt = $pdo->prepare('DELETE FROM ppdb WHERE id = ?');
            $stmt->execute([$input['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Deleted']);
            exit;
        }

        // UPDATE STATUS
        if (! empty($input['id']) && ! empty($input['status_pendaftaran'])) {
            $stmt = $pdo->prepare('UPDATE ppdb SET status_pendaftaran = ? WHERE id = ?');
            $stmt->execute([$input['status_pendaftaran'], $input['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Status Pendaftaran Updated']);
            exit;
        }

        // UPDATE STATUS BERKAS
        if (! empty($input['id']) && ! empty($input['status_berkas'])) {
            $stmt = $pdo->prepare('UPDATE ppdb SET status_berkas = ? WHERE id = ?');
            $stmt->execute([$input['status_berkas'], $input['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Status Berkas Updated']);
            exit;
        }
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
