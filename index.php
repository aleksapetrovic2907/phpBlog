<?php
require_once __DIR__ . '/vendor/autoload.php';

$basePath = '/phpblog';
$requestUri = str_ireplace($basePath, '', $_SERVER['REQUEST_URI']);
$requestUri = trim($requestUri);
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (str_starts_with($requestUri, '/api/')) {
    require 'api_router.php';
} else {
    require 'view_router.php';
}
?>