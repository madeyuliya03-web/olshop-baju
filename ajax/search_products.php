<?php
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
    
    $query = "SELECT p.*, k.nama as kategori FROM produk p 
              JOIN kategori k ON p.kategori_id = k.id 
              WHERE p.nama LIKE '%$search%' OR p.deskripsi LIKE '%$search%' 
              LIMIT 20";
    
    $result = $conn->query($query);
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    echo json_encode($products);
}
?>