<?php
// create_admin.php

require_once 'includes/db.php';

$email = "admin@example.com";
$password = password_hash("admin123", PASSWORD_DEFAULT); // change this to a secure password
$role = "admin";

// Check if admin already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$existing = $stmt->fetch();

if ($existing) {
    echo "Admin already exists with this email.";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$email, $password, $role])) {
        echo "Admin user created successfully.";
    } else {
        echo "Error creating admin user.";
    }
}
