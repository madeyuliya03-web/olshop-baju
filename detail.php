<?php
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: produk.php');
    exit;
}

$produk_id = intval($_GET['id']);
$query = "SELECT p.*, k.nama as kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id WHERE p.id = $produk_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header('Location: produk.php');
    exit;
}

$produk = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produk['nama']); ?> - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .detail-container {
            max-width: 1000px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        .product-image-detail {
            width: 100%;
            height: 400px;
            background: #e0e0e0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            overflow: hidden;
        }
        .product-image-detail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .quantity-input {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin: 1rem 0;
        }
        .quantity-input button {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .quantity-input input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            height: 40px;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container">
        <div class="detail-container">
            <a href="produk.php" style="color: #667eea; text-decoration: none; margin-bottom: 1rem; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Kembali ke Produk
            </a>

            <div class="detail-grid">
                <div>
                    <div class="product-image-detail">
                        <?php if ($produk['gambar']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($produk['gambar']); ?>" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h1><?php echo htmlspecialchars($produk['nama']); ?></h1>
                    <p style="color: #999; margin-bottom: 1rem;">Kategori: <?php echo htmlspecialchars($produk['kategori']); ?></p>
                    
                    <div style="background: #f0f0f0; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">Harga</div>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #667eea;">
                            Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <h3>Stok Tersedia</h3>
                        <div style="font-size: 1.3rem; margin-bottom: 1rem;">
                            <?php if ($produk['stok'] > 0): ?>
                                <span class="stok-tersedia"><?php echo $produk['stok']; ?> item</span>
                            <?php else: ?>
                                <span class="stok-habis">Stok Habis</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <h3>Deskripsi Produk</h3>
                        <p style="line-height: 1.8; color: #666;"><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
                    </div>

                    <?php if ($produk['stok'] > 0): ?>
                        <div style="margin-bottom: 2rem;">
                            <h3>Jumlah Pembelian</h3>
                            <div class="quantity-input">
                                <button onclick="decreaseQty()" id="btnMinus">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="<?php echo $produk['stok']; ?>">
                                <button onclick="increaseQty()" id="btnPlus">+</button>
                            </div>
                            <p style="font-size: 0.9rem; color: #999;">Maks: <?php echo $produk['stok']; ?> item</p>
                        </div>

                        <button class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;" onclick="addToCartDetail(<?php echo $produk['id']; ?>)">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary" style="width: 100%; padding: 1rem; font-size: 1.1rem;" disabled>
                            Stok Habis
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        function increaseQty() {
            const maxStok = <?php echo $produk['stok']; ?>;
            const input = document.getElementById('quantity');
            if (parseInt(input.value) < maxStok) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function addToCartDetail(produkId) {
            const quantity = document.getElementById('quantity').value;
            
            fetch('ajax/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'produk_id=' + produkId + '&quantity=' + quantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Produk berhasil ditambahkan ke keranjang', 'success');
                    updateCartCount();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            });
        }
    </script>
</body>
</html>