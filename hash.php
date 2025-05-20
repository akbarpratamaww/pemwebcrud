<?php
// Menghasilkan hash untuk password "admin123"
$password = "admin123";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hash untuk password 'admin123' adalah: " . $hashed_password;
?>