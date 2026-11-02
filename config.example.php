<?php
    declare(strict_types=1);
    session_start();

    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'atelier');
    define('DB_USER', 'root');
    define('DB_PASS', 'TU_CONTRASEÑA_LOCAL');
    define('SITE_NAME', 'ATELIER');

    function db(): PDO {
        static $pdo = null;
        if ($pdo === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return $pdo;
    }
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
    function money(float|int|string $amount): string {
        return '$' . number_format((float)$amount, 2) . ' MXN';
    }
    function is_logged_in(): bool { 
        return isset($_SESSION['usuario']['id']); 
        }
    function is_admin(): bool { 
        return is_logged_in() && ($_SESSION['usuario']['rol'] ?? '') === 'admin'; 
        }
    function require_admin(): void {
        if (!is_admin()) {
            header('Location: ../login.php?next=admin');
            exit;
        }
    }

    function csrf_token(): string {
        if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf'];
    }
    function verify_csrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf'] ?? '';
            if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
                http_response_code(419);
                exit('La sesión del formulario expiró. Regresa e inténtalo de nuevo.');
            }
        }
    }