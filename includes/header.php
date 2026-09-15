<?php
session_start();
include 'config/db.php';

// Fungsi untuk cek apakah pengguna login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk cek apakah admin login
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Fungsi redirect jika belum login
function checkLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Fungsi redirect jika admin belum login
function checkAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin/login.php');
        exit;
    }
}
?>
