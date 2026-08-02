<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

// Mapping Input Frontend -> Kolom Database
// Frontend: nama_lengkap, nisn, nik, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, asal_sekolah, tahun_lulus, nama_ayah, nama_ibu, no_hp, jurusan
// Database: no_registrasi, nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, asal_sekolah, tahun_lulus, nama_ayah, nama_ibu, no_hp, jurusan_pilihan

$requiredFields = ['nama_lengkap', 'nisn', 'nik', 'jenis_kelamin', 'jurusan', 'no_hp', 'asal_sekolah'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Field $field wajib diisi!"]);
        exit;
    }
}

try {
    // Generate No Registrasi Unik: REG-YYYY-XXXX (Contoh: REG-2026-0001)
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM ppdb");
    $row = $stmt->fetch();
    $nextId = ($row['max_id'] ?? 0) + 1;
    $noRegistrasi = 'REG-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    $sql = "INSERT INTO ppdb (
        no_registrasi, nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat,
        asal_sekolah, tahun_lulus, nama_ayah, nama_ibu, no_hp, jurusan_pilihan, status_pendaftaran
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, 'Pending'
    )";

    $stmt = $pdo->prepare($sql);
    
    // Konversi Jenis Kelamin: Frontend 'Laki-laki'/'Perempuan' -> DB 'L'/'P'
    $jk = ($input['jenis_kelamin'] == 'Laki-laki') ? 'L' : 'P';
    
    $stmt->execute([
        $noRegistrasi,
        $input['nama_lengkap'],
        $input['nisn'],
        $input['nik'],
        $input['tempat_lahir'] ?? null,
        $input['tanggal_lahir'] ?? null,
        $jk,
        $input['alamat'] ?? null,
        $input['asal_sekolah'],
        $input['tahun_lulus'] ?? date('Y'),
        $input['nama_ayah'] ?? null,
        $input['nama_ibu'] ?? null,
        $input['no_hp'],
        $input['jurusan'], // jurusan_pilihan
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => "Pendaftaran berhasil! Nomor Registrasi Anda: $noRegistrasi. Simpan nomor ini."
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() == 23000) {
        $msg = 'Data duplikat (NISN/No Registrasi sudah ada).';
    } else {
        $msg = 'Database Error: ' . $e->getMessage();
    }
    echo json_encode(['status' => 'error', 'message' => $msg]);
}
?>
