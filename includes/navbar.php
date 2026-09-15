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
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php"><i class="fas fa-shopping-bag"></i> Olshop Baju</a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Beranda</a>
                </li>
                <li class="nav-item">
                    <a href="produk.php" class="nav-link">Produk</a>
                </li>
                <li class="nav-item">
                    <a href="keranjang.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                        <?php
                        if (isLoggedIn()) {
                            $user_id = $_SESSION['user_id'];
                            $result = $conn->query("SELECT COUNT(*) as total FROM keranjang WHERE pengguna_id = $user_id");
                            $row = $result->fetch_assoc();
                            if ($row['total'] > 0) {
                                echo '<span class="cart-badge">' . $row['total'] . '</span>';
                            }
                        }
                        ?>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <div class="dropdown-menu">
                            <a href="pesanan.php">Pesanan Saya</a>
                            <a href="profil.php">Profil</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link btn-login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link btn-register">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
