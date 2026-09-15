<?php
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Silakan login']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity < 1) $quantity = 1;
    
    // Cek apakah cart milik user
    $check = $conn->query("SELECT produk_id FROM keranjang WHERE id = $cart_id AND pengguna_id = $user_id");
    
    if ($check->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
        exit;
    }
    
    $row = $check->fetch_assoc();
    $produk_id = $row['produk_id'];
    
    // Cek stok
    $stok_check = $conn->query("SELECT stok FROM produk WHERE id = $produk_id");
    $stok_data = $stok_check->fetch_assoc();
    
    if ($quantity > $stok_data['stok']) {
        echo json_encode(['success' => false, 'message' => 'Stok tidak cukup']);
        exit;
    }
    
    $update = $conn->query("UPDATE keranjang SET jumlah = $quantity WHERE id = $cart_id");
    
    echo json_encode(['success' => true, 'message' => 'Keranjang diperbarui']);
}
?>