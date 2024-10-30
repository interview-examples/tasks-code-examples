<?php

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);
    $role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

    if ($username && $email && $role_id) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $password, $role_id);

        if ($stmt->execute()) {
            echo "Registered successfully!";
        } else {
            http_response_code(400);
            echo "Error: " . $stmt->error;
        }
    } else {
        http_response_code(400);
        echo "Invalid parameters (username, email or role ID).";
    }

    $stmt->close();
}
?>

<form method="POST" action="">
    <input type="text" name="username" required placeholder="User name">
    <input type="email" name="email" required placeholder="E-mail">
    <input type="password" name="password" required placeholder="Password">
    <input type="number" name="role_id" required placeholder="Role ID">
    <button type="submit">Register</button>
</form>
