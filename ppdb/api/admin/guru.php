<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once '../../config/database.php';

session_start();
// Simple auth check (disable for testing if needed)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    // === GET: List All Guru ===
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM guru ORDER BY created_at DESC");
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // === POST: Add, Update, Delete ===
    if ($method === 'POST') {
        // Handle Delete via POST (common workaround)
        if (isset($input['_method']) && $input['_method'] === 'DELETE') {
            if (empty($input['id'])) throw new Exception("ID required");
            
            $stmt = $pdo->prepare("DELETE FROM guru WHERE id = ?");
            $stmt->execute([$input['id']]);
            
            echo json_encode(['status' => 'success', 'message' => 'Deleted']);
            exit;
        }

        // Validate Input
        if (empty($input['nama'])) throw new Exception("Nama wajib diisi");

        // Prepare columns
        $nama = $input['nama'];
        $nip = $input['nip'] ?? '';
        $jabatan = $input['jabatan'] ?? '';
        $mapel = $input['mapel'] ?? '';
        $kategori = $input['kategori'] ?? 'Umum';
        $status = $input['status_kepegawaian'] ?? 'Honorer';
        $golongan = $input['golongan'] ?? '';
        $kontak = $input['kontak'] ?? '';

        if (!empty($input['id'])) {
            // === UPDATE ===
            $sql = "UPDATE guru SET nama=?, nip=?, jabatan=?, mapel=?, kategori=?, status_kepegawaian=?, golongan=?, kontak=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $nip, $jabatan, $mapel, $kategori, $status, $golongan, $kontak, $input['id']]);
            $msg = "Updated";
        } else {
            // === INSERT ===
            $sql = "INSERT INTO guru (nama, nip, jabatan, mapel, kategori, status_kepegawaian, golongan, kontak) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $nip, $jabatan, $mapel, $kategori, $status, $golongan, $kontak]);
            $msg = "Created";
        }

        echo json_encode(['status' => 'success', 'message' => $msg]);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
