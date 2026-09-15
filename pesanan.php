<?php
include 'includes/header.php';
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container" style="margin: 3rem 0;">
        <h1><i class="fas fa-file-invoice"></i> Pesanan Saya</h1>

        <?php
        if (isset($_SESSION['success_message'])):
        ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></span>
            </div>
        <?php endif; ?>

        <?php
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM pesanan WHERE pengguna_id = $user_id ORDER BY created_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
        ?>
            <div style="overflow-x: auto; margin: 2rem 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()):
                            $status_class = 'style="color: #999;"';
                            if ($row['status'] == 'diproses') {
                                $status_class = 'style="color: #ffc107;"';
                            } elseif ($row['status'] == 'dikirim') {
                                $status_class = 'style="color: #17a2b8;"';
                            } elseif ($row['status'] == 'selesai') {
                                $status_class = 'style="color: #28a745;"';
                            }
                        ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <span <?php echo $status_class; ?>>
                                        <?php 
                                        $status_label = ucfirst($row['status']);
                                        if ($row['status'] == 'pending') $status_label = 'Menunggu';
                                        echo $status_label;
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="pesanan_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 4rem 2rem;">
                <i class="fas fa-file-invoice" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h2 style="color: #999; margin-bottom: 1rem;">Belum Ada Pesanan</h2>
                <p style="color: #999; margin-bottom: 2rem;">Mulai belanja sekarang untuk membuat pesanan pertama Anda</p>
                <a href="produk.php" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>