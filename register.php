<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $no_telepon = $conn->real_escape_string($_POST['no_telepon']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    
    // Cek email sudah terdaftar
    $check = $conn->query("SELECT id FROM pengguna WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = 'Email sudah terdaftar';
    } else {
        $query = "INSERT INTO pengguna (nama, email, password, no_telepon, alamat) 
                  VALUES ('$nama', '$email', '$password', '$no_telepon', '$alamat')";
        
        if ($conn->query($query)) {
            $success = 'Pendaftaran berhasil! Silakan login';
        } else {
            $error = 'Pendaftaran gagal: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Olshop Baju</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-container {
            max-width: 500px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .auth-container h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="auth-container">
        <h1><i class="fas fa-user-plus"></i> Daftar Akun</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="no_telepon">No. Telepon</label>
                <input type="tel" id="no_telepon" name="no_telepon">
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Daftar
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 1rem;">
            Sudah punya akun? <a href="login.php" style="color: #667eea; text-decoration: none;">Login di sini</a>
        </p>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>