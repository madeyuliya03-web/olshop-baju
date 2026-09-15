<?php
include '../includes/header.php';
checkAdminLogin();

// Proses tambah kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_kategori') {
        $nama = $conn->real_escape_string($_POST['nama']);
        $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
        
        $insert = $conn->query("INSERT INTO kategori (nama, deskripsi) VALUES ('$nama', '$deskripsi')");
        if ($insert) {
            $success = 'Kategori berhasil ditambahkan';
        } else {
            $error = 'Gagal menambahkan kategori';
        }
    }
}

// Ambil semua kategori
$kategori_result = $conn->query("SELECT * FROM kategori ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin Olshop Baju</title>
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
                <h1><i class="fas fa-list"></i> Kelola Kategori</h1>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <!-- Form Tambah Kategori -->
            <div style="background: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Tambah Kategori Baru</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>

                    <input type="hidden" name="action" value="add_kategori">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </button>
                </form>
            </div>

            <!-- Daftar Kategori -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Daftar Kategori</h2>
                
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = $kategori_result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 50)); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="edit_kategori.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="hapus_kategori.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Yakin ingin menghapus?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>