<?php
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['produk_id'])) {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $produk_id = intval($_POST['produk_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($quantity < 1) $quantity = 1;
        
        // Cek stok produk
        $stok_check = $conn->query("SELECT stok FROM produk WHERE id = $produk_id");
        $stok_data = $stok_check->fetch_assoc();
        
        if (!$stok_data || $stok_data['stok'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Stok tidak cukup']);
            exit;
        }
        
        // Cek apakah sudah ada di keranjang
        $check = $conn->query("SELECT id, jumlah FROM keranjang WHERE pengguna_id = $user_id AND produk_id = $produk_id");
        
        if ($check->num_rows > 0) {
            $item = $check->fetch_assoc();
            $new_qty = $item['jumlah'] + $quantity;
            
            if ($new_qty > $stok_data['stok']) {
                echo json_encode(['success' => false, 'message' => 'Stok tidak cukup']);
                exit;
            }
            
            $update = $conn->query("UPDATE keranjang SET jumlah = $new_qty WHERE id = " . $item['id']);
        } else {
            $insert = $conn->query("INSERT INTO keranjang (pengguna_id, produk_id, jumlah) VALUES ($user_id, $produk_id, $quantity)");
        }
        
        echo json_encode(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }
}
?>