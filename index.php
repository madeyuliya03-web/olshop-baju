<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olshop Baju - Toko Online Baju Terpercaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di Olshop Baju</h1>
            <p>Toko online baju terpercaya dengan koleksi lengkap dan harga terjangkau</p>
            <a href="produk.php" class="hero-btn">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <h2 style="margin-bottom: 2rem; text-align: center;">Produk Terbaru</h2>
        
        <?php
        // Ambil produk terbaru (max 8)
        $query = "SELECT p.*, k.nama as kategori 
                  FROM produk p 
                  JOIN kategori k ON p.kategori_id = k.id 
                  ORDER BY p.created_at DESC 
                  LIMIT 8";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            echo '<div class="products-grid">';
            while ($row = $result->fetch_assoc()) {
                $harga = number_format($row['harga'], 0, ',', '.');
                $stok = $row['stok'];
                echo '<div class="product-card">
                    <div class="product-image">';
                
                if ($row['gambar']) {
                    echo '<img src="uploads/' . htmlspecialchars($row['gambar']) . '" alt="' . htmlspecialchars($row['nama']) . '">';
                } else {
                    echo '<i class="fas fa-image"></i>';
                }
                
                echo '</div>
                    <div class="product-info">
                        <div class="product-name">' . htmlspecialchars($row['nama']) . '</div>
                        <div class="product-category">' . htmlspecialchars($row['kategori']) . '</div>
                        <div class="product-description">' . substr(htmlspecialchars($row['deskripsi']), 0, 50) . '...</div>
                        <div class="product-price">Rp ' . $harga . '</div>
                        <div class="product-stok">';
                
                if ($stok > 0) {
                    echo '<span class="stok-tersedia">Stok: ' . $stok . '</span>';
                } else {
                    echo '<span class="stok-habis">Stok Habis</span>';
                }
                
                echo '</div>
                        <div class="product-actions">
                            <a href="detail.php?id=' . $row['id'] . '" class="btn btn-secondary">
                                <i class="fas fa-eye"></i> Lihat
                            </a>';
                
                if ($stok > 0) {
                    echo '<button class="btn btn-primary" onclick="addToCart(' . $row['id'] . ')">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </button>';
                } else {
                    echo '<button class="btn btn-secondary" disabled>Habis</button>';
                }
                
                echo '</div>
                    </div>
                </div>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-center">Produk tidak tersedia</p>';
        }
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>