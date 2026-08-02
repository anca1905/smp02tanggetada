<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once '../../config/database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    // === GET: List All Berita ===
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC");
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // === POST: Add, Update, Delete ===
    if ($method === 'POST') {
        // Check for DELETE method spoofing
        if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            // Handle delete logic here if needed, or stick to raw input for purely DELETE requests if client sends JSON
            // But since we use FormData for everything now, let's keep it consistent.
        }

        // For actual Delete via JSON, the client might send raw JSON. 
        // Let's support both or check content type. 
        // If content-type is json, decode it.
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['_method']) && $input['_method'] === 'DELETE') {
                if (empty($input['id'])) throw new Exception("ID required");

                // Get image path to delete file
                $stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
                $stmt->execute([$input['id']]);
                $oldImg = $stmt->fetchColumn();

                if ($oldImg && file_exists('../../' . $oldImg) && strpos($oldImg, 'news-placeholder') === false) {
                    unlink('../../' . $oldImg);
                }

                $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
                $stmt->execute([$input['id']]);
                echo json_encode(['status' => 'success', 'message' => 'Deleted']);
                exit;
            }
        }

        // --- Normal Form Submit (Multipart/Form-Data) ---
        $judul = $_POST['judul'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
        $ringkasan = $_POST['ringkasan'];
        $isi = $_POST['isi'];
        $kategori = $_POST['kategori'];
        $status = $_POST['status'];
        $penulis = $_SESSION['user_name'] ?? 'Admin'; // Should ideally come from session
        $tanggal = date('Y-m-d');

        $gambarPath = null;

        // Handle File Upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/uploads/berita/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $fileExt = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExt, $allowed)) {
                $fileName = 'news_' . time() . '.' . $fileExt;
                $destPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $destPath)) {
                    $gambarPath = 'assets/uploads/berita/' . $fileName;
                }
            }
        }

        if (!empty($_POST['id'])) {
            // Update
            $id = $_POST['id'];

            $sql = "UPDATE berita SET judul=?, slug=?, ringkasan=?, isi=?, kategori=?, status=?";
            $params = [$judul, $slug, $ringkasan, $isi, $kategori, $status];

            if ($gambarPath) {
                // Delete old image
                $stmt = $pdo->prepare("SELECT gambar FROM berita WHERE id = ?");
                $stmt->execute([$id]);
                $oldImg = $stmt->fetchColumn();
                if ($oldImg && file_exists('../../' . $oldImg) && strpos($oldImg, 'news-placeholder') === false) {
                    unlink('../../' . $oldImg);
                }

                $sql .= ", gambar=?";
                $params[] = $gambarPath;
            }

            $sql .= " WHERE id=?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        } else {
            // Insert
            $gambar = $gambarPath ?? 'assets/img/news-placeholder.jpg';
            $sql = "INSERT INTO berita (judul, slug, ringkasan, isi, kategori, status, penulis, tanggal, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $slug, $ringkasan, $isi, $kategori, $status, $penulis, $tanggal, $gambar]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Berita saved']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
