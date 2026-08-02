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
    // === GET: Fetch Settings ===
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM pengaturan LIMIT 1");
        $data = $stmt->fetch();
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // === POST: Update Settings ===
    if ($method === 'POST') {
        
        // Handle FormData (files + text)
        $id = $_POST['id'] ?? 1;
        $nama_sekolah = $_POST['nama_sekolah'];
        $slogan = $_POST['slogan'];
        $deskripsi = $_POST['deskripsi'];
        $email = $_POST['email'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];
        $maps = $_POST['maps_embed'];
        $fb = $_POST['facebook'];
        $ig = $_POST['instagram'];
        $yt = $_POST['youtube'];
        $mode_maintenance = filter_var($_POST['mode_maintenance'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $buka_ppdb = filter_var($_POST['buka_ppdb'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        // Handle File Upload (Brosur)
        $file_brosur_sql = "";
        $params = [
            $nama_sekolah, $slogan, $deskripsi, $email, $telepon, $alamat, $maps,
            $fb, $ig, $yt, $mode_maintenance, $buka_ppdb
        ];

        if (isset($_FILES['file_brosur']) && $_FILES['file_brosur']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $fileExt = strtolower(pathinfo($_FILES['file_brosur']['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

            if (in_array($fileExt, $allowed)) {
                $fileName = 'brosur_ppdb_' . time() . '.' . $fileExt;
                $destPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['file_brosur']['tmp_name'], $destPath)) {
                    $file_brosur_sql = ", file_brosur=?";
                    $params[] = $fileName;
                }
            }
        }

        $params[] = $id;
        
        $sql = "UPDATE pengaturan SET 
                nama_sekolah=?, slogan=?, deskripsi=?, email=?, telepon=?, alamat=?, maps_embed=?,
                facebook=?, instagram=?, youtube=?, mode_maintenance=?, buka_ppdb=?" . $file_brosur_sql . "
                WHERE id=?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status' => 'success', 'message' => 'Pengaturan disimpan']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
