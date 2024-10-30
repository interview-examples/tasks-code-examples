<?php

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_name = filter_input(INPUT_POST, 'role_name', FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->bind_param("s", $role_name);

    if ($stmt->execute()) {
        echo "The role has been created!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="POST" action="">
    <input type="text" name="role_name" required placeholder="Role name">
    <button type="submit">Create role</button>
</form>
