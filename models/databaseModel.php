<?php

require "models/config.php";

function getDatabaseConnection() {
    global $dbhost, $dbname, $dbuser, $dbpass;

    try {
        $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $dbuser, $dbpass, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function getAllByTable($table) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM `$table`");
    $stmt->execute();
    return $stmt->fetchAll();
}
