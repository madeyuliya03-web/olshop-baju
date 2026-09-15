<?php
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Silakan login']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $cart_id = intval($_POST['cart_id']);
    
    // Cek apakah cart milik user
    $check = $conn->query("SELECT id FROM keranjang WHERE id = $cart_id AND pengguna_id = $user_id");
    
    if ($check->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
        exit;
    }
    
    $delete = $conn->query("DELETE FROM keranjang WHERE id = $cart_id");
    
    echo json_encode(['success' => true, 'message' => 'Produk dihapus dari keranjang']);
}
?>