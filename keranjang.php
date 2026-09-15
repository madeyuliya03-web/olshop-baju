<?php
include 'includes/header.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container" style="margin: 3rem 0;">
        <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>

        <?php
        $user_id = $_SESSION['user_id'];
        $query = "SELECT k.*, p.nama, p.harga, p.gambar, p.stok FROM keranjang k 
                  JOIN produk p ON k.produk_id = p.id 
                  WHERE k.pengguna_id = $user_id";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
            $total = 0;
        ?>
            <div style="overflow-x: auto; margin: 2rem 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()):
                            $subtotal = $row['harga'] * $row['jumlah'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 80px; height: 80px; background: #e0e0e0; border-radius: 5px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <?php if ($row['gambar']): ?>
                                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: bold;"><?php echo htmlspecialchars($row['nama']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <button onclick="decreaseQty(<?php echo $row['id']; ?>, <?php echo $row['jumlah']; ?>)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">-</button>
                                        <span style="min-width: 40px; text-align: center;"><?php echo $row['jumlah']; ?></span>
                                        <button onclick="increaseQty(<?php echo $row['id']; ?>, <?php echo $row['jumlah']; ?>, <?php echo $row['stok']; ?>)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">+</button>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <button class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="removeFromCart(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 400px; margin-left: auto;">
                <h2 style="margin-bottom: 1.5rem;">Ringkasan Belanja</h2>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 1rem;">
                    <span>Total Harga:</span>
                    <span style="font-weight: bold;">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
                <a href="checkout.php" class="btn btn-primary" style="width: 100%; padding: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                </a>
                <a href="produk.php" class="btn btn-secondary" style="width: 100%; padding: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <i class="fas fa-shopping-bag"></i> Lanjut Belanja
                </a>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 4rem 2rem;">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h2 style="color: #999; margin-bottom: 1rem;">Keranjang Anda Kosong</h2>
                <p style="color: #999; margin-bottom: 2rem;">Mulai belanja dan tambahkan produk favorit Anda</p>
                <a href="produk.php" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        function increaseQty(cartId, currentQty, maxStok) {
            if (currentQty < maxStok) {
                updateCartQuantity(cartId, currentQty + 1);
            }
        }

        function decreaseQty(cartId, currentQty) {
            if (currentQty > 1) {
                updateCartQuantity(cartId, currentQty - 1);
            }
        }
    </script>
</body>
</html>