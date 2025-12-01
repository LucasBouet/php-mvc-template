<?php

$path = match($_SERVER["REQUEST_URI"]) {
    '/' => 'controllers/homeController.php',
    '/api/status' => 'api/status.php',
    default => '404.php',
};

require $path;