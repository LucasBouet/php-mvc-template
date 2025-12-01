<?php

$path = match(explode('?', $_SERVER["REQUEST_URI"])[0]) {
    '/' => 'controllers/homeController.php',
    '/api/status' => 'api/status.php',
    default => '404.php',
};

require $path;
