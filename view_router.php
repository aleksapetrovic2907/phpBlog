<?php
// ! Placeholder values - these pages do not exist yet.
switch ($requestUri) {
    case '/':
        require 'views/home.php';
        break;
    case '/login':
        require 'views/login.php';
        break;
    case '/register':
        require 'views/register.php';
        break;
    default:
        require 'views/404.php';
}
?>