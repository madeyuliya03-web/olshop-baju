<?php
// Fungsi-fungsi utility

// Cek apakah pengguna login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Cek apakah admin login
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect jika belum login
function checkLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Redirect jika admin belum login
function checkAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin/login.php');
        exit;
    }
}

// Format Rupiah
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>
