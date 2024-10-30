<?php

require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        echo "Logged in";
    } else {
        echo "Wrong password or user name";
    }

    $stmt->close();
}
?>

<form method="POST" action="">
    <input type="text" name="username" required placeholder="User name">
    <input type="password" name="password" required placeholder="User password">
    <button type="submit">Log In</button>
</form>
