<?php

$path = match($_SERVER["REQUEST_URI"]) {
    '/' => 'controllers/homeController.php',
    default => '404.php',
};

require $path;