<?php

header('Content-Type: application/json; charset=utf-8');

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

if (str_contains($path, '/auth/')) {
    require __DIR__ . '/routes/auth.php';
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Sweet Bakery API is running.'
], JSON_UNESCAPED_UNICODE);