<?php
include 'includes/header.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container" style="margin: 3rem 0;">
        <h1><i class="fas fa-credit-card"></i> Checkout</h1>

        <?php
        $user_id = $_SESSION['user_id'];
        
        // Ambil data keranjang
        $query = "SELECT k.*, p.harga FROM keranjang k 
                  JOIN produk p ON k.produk_id = p.id 
                  WHERE k.pengguna_id = $user_id";
        $result = $conn->query($query);

        if ($result->num_rows == 0) {
            echo '<div style="text-align: center; padding: 2rem;">
                <p style="margin-bottom: 1rem;">Keranjang Anda kosong</p>
                <a href="produk.php" class="btn btn-primary">Kembali Belanja</a>
            </div>';
            include 'includes/footer.php';
            exit;
        }

        // Hitung total
        $total = 0;
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['harga'] * $row['jumlah'];
            $total += $subtotal;
            $items[] = $row;
        }

        // Proses checkout
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Mulai transaksi
            $conn->begin_transaction();
            
            try {
                // Buat pesanan
                $insert_order = $conn->prepare("INSERT INTO pesanan (pengguna_id, total, status) VALUES (?, ?, 'pending')");
                $insert_order->bind_param("id", $user_id, $total);
                $insert_order->execute();
                $order_id = $conn->insert_id;

                // Tambah detail pesanan dan hapus dari keranjang
                foreach ($items as $item) {
                    $insert_detail = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga) VALUES (?, ?, ?, ?)");
                    $insert_detail->bind_param("iiii", $order_id, $item['produk_id'], $item['jumlah'], $item['harga']);
                    $insert_detail->execute();
                    $insert_detail->close();
                }

                // Hapus semua item dari keranjang
                $delete_cart = $conn->query("DELETE FROM keranjang WHERE pengguna_id = $user_id");

                $conn->commit();
                
                // Redirect ke halaman pesanan
                $_SESSION['success_message'] = 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.';
                header('Location: pesanan.php');
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $error = 'Terjadi kesalahan: ' . $e->getMessage();
            }
        }
        ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin: 2rem 0;">
            <!-- Order Summary -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Detail Pesanan</h2>
                
                <?php foreach ($items as $item): ?>
                    <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #ddd;">
                        <div>
                            <p style="font-weight: bold;"><?php echo htmlspecialchars($item['nama']); ?></p>
                            <p style="color: #999; font-size: 0.9rem;"><?php echo $item['jumlah']; ?> x Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                        </div>
                        <p style="font-weight: bold;">Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></p>
                    </div>
                <?php endforeach; ?>

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; font-size: 1.3rem; font-weight: bold; border-top: 2px solid #667eea; margin-top: 1rem;">
                    <span>Total:</span>
                    <span style="color: #667eea;">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
            </div>

            <!-- Checkout Form -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Konfirmasi Pesanan</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php
                // Ambil data pengguna
                $user_result = $conn->query("SELECT * FROM pengguna WHERE id = $user_id");
                $user = $user_result->fetch_assoc();
                ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['nama']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="tel" value="<?php echo htmlspecialchars($user['no_telepon']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Alamat Pengiriman</label>
                        <textarea readonly><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: bold;">
                        <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                    </button>
                </form>

                <a href="keranjang.php" class="btn btn-secondary" style="width: 100%; padding: 1rem; margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                </a>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>