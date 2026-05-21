<?php
// ================= DATABASE CONFIGURATION =================
// UPDATE THESE WITH YOUR HOST AFRICA DETAILS
define('DB_HOST', 'localhost');
define('DB_NAME', 'tebscoza_tebscoza_admin');  // ← Your database name
define('DB_USER', 'tebscoza_admin');           // ← Your database username
define('DB_PASS', 'Tebs@1234');                // ← Your database password

// ================= APPLICATION SETTINGS =================
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB max file size
define('ALLOWED_TYPES', ['application/pdf']); // Only PDFs allowed

// ================= SESSION START =================
session_start();

// ================= DATABASE CONNECTION =================
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        // Log error for admin, show generic message to user
        error_log("Database connection failed: " . $e->getMessage());
        die("Database connection failed. Please contact support.");
    }
}

// ================= AUTHENTICATION CHECK =================
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

// ================= SECURITY HELPER =================
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
?>