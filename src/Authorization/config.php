<?php

$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'auth_system',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4'
];
// Note: MySQLi realization
$conn = new mysqli(
    $dbConfig['host'],
    $dbConfig['username'],
    $dbConfig['password'],
    $dbConfig['dbname']
);

if ($conn->connect_error) {
    throw new RuntimeException('Connection failed: ' . $conn->connect_error);
    // die("Connection failed: " . $conn->connect_error); Note:: depend on technical requests
}

// Note: PDO version
/*
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    throw new RuntimeException('Connection failed: ' . $e->getMessage());
}*/
