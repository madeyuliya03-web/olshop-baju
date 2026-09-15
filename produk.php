<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container" style="margin: 3rem 0;">
        <h1>Daftar Produk</h1>
        
        <!-- Filter & Search -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari produk..." style="flex: 1; min-width: 200px;">
            
            <select id="categoryFilter" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                <option value="">Semua Kategori</option>
                <?php
                $kategori_result = $conn->query("SELECT * FROM kategori");
                while ($kat = $kategori_result->fetch_assoc()) {
                    echo '<option value="' . $kat['id'] . '">' . htmlspecialchars($kat['nama']) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsContainer">
            <?php
            // Filter produk
            $kategori_id = $_GET['kategori'] ?? '';
            $search = $_GET['search'] ?? '';
            
            $query = "SELECT p.*, k.nama as kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id WHERE 1=1";
            
            if ($kategori_id) {
                $query .= " AND p.kategori_id = " . intval($kategori_id);
            }
            
            if ($search) {
                $search = $conn->real_escape_string($search);
                $query .= " AND (p.nama LIKE '%$search%' OR p.deskripsi LIKE '%$search%')";
            }
            
            $query .= " ORDER BY p.created_at DESC";
            
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
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
            } else {
                echo '<p class="text-center">Produk tidak ditemukan</p>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Filter berdasarkan kategori
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const kategori = this.value;
            window.location.href = kategori ? 'produk.php?kategori=' + kategori : 'produk.php';
        });

        // Search produk
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const search = this.value;
                window.location.href = search ? 'produk.php?search=' + encodeURIComponent(search) : 'produk.php';
            }
        });
    </script>
</body>
</html>