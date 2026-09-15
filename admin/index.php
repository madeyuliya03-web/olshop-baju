<?php
include '../includes/header.php';
checkAdminLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Olshop Baju</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/admin_navbar.php'; ?>

    <div class="admin-container">
        <div class="admin-sidebar">
            <?php include '../includes/admin_sidebar.php'; ?>
        </div>

        <main class="admin-main">
            <div class="admin-header">
                <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
            </div>

            <!-- Stats Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <?php
                // Total Produk
                $total_produk = $conn->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
                
                // Total Kategori
                $total_kategori = $conn->query("SELECT COUNT(*) as total FROM kategori")->fetch_assoc()['total'];
                
                // Total Pengguna
                $total_pengguna = $conn->query("SELECT COUNT(*) as total FROM pengguna")->fetch_assoc()['total'];
                
                // Total Pesanan
                $total_pesanan = $conn->query("SELECT COUNT(*) as total FROM pesanan")->fetch_assoc()['total'];
                
                // Total Revenue
                $total_revenue = $conn->query("SELECT SUM(total) as total FROM pesanan WHERE status = 'selesai'")->fetch_assoc()['total'] ?? 0;
                ?>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #e7f3ff; color: #0066cc;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value"><?php echo $total_produk; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #f0f7ff; color: #003399;">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Kategori</div>
                        <div class="stat-value"><?php echo $total_kategori; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff4e6; color: #ff9800;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Pengguna</div>
                        <div class="stat-value"><?php echo $total_pengguna; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #f0f9f0; color: #28a745;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Pesanan</div>
                        <div class="stat-value"><?php echo $total_pesanan; ?></div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-clock"></i> Pesanan Terbaru</h2>
                
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $order_result = $conn->query("SELECT p.*, u.nama FROM pesanan p JOIN pengguna u ON p.pengguna_id = u.id ORDER BY p.created_at DESC LIMIT 10");
                            
                            if ($order_result->num_rows > 0):
                                while ($row = $order_result->fetch_assoc()):
                                    $status_color = 'style="color: #999;"';
                                    if ($row['status'] == 'diproses') $status_color = 'style="color: #ffc107;"';
                                    elseif ($row['status'] == 'dikirim') $status_color = 'style="color: #17a2b8;"';
                                    elseif ($row['status'] == 'selesai') $status_color = 'style="color: #28a745;"';
                            ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                                    <td><span <?php echo $status_color; ?>><?php echo ucfirst($row['status']); ?></span></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="pesanan.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #999;">Belum ada pesanan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>