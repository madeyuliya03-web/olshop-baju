<?php
include '../includes/header.php';

if (!isLoggedIn()) {
    echo json_encode(['count' => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT COUNT(*) as total FROM keranjang WHERE pengguna_id = $user_id");
$row = $result->fetch_assoc();

echo json_encode(['count' => $row['total']]);
?>