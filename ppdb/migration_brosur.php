<?php
require_once 'config/database.php';

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM pengaturan LIKE 'file_brosur'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $sql = "ALTER TABLE pengaturan ADD COLUMN file_brosur VARCHAR(255) DEFAULT NULL AFTER maps_embed";
        $pdo->exec($sql);
        echo "Column 'file_brosur' added successfully.\n";
    } else {
        echo "Column 'file_brosur' already exists.\n";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
