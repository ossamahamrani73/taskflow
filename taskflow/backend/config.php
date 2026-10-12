<?php
// ============================================================
// TaskFlow — Backend configuration
// Change these constants to match your own database setup.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'taskflow');
define('DB_USER', 'root');
define('DB_PASS', ''); // empty by default on XAMPP — set your own password

// --- HTTP headers ---------------------------------------------------------
header('Content-Type: application/json; charset=utf-8');

// CORS: allow the Vite dev frontend to call this API
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Answer preflight requests immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// --- PDO connection -------------------------------------------------------
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed. Check config.php.']);
    exit;
}
