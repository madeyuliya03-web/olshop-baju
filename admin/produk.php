<?php
include '../includes/header.php';
checkAdminLogin();

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_produk') {
        $kategori_id = intval($_POST['kategori_id']);
        $nama = $conn->real_escape_string($_POST['nama']);
        $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
        $harga = floatval($_POST['harga']);
        $stok = intval($_POST['stok']);
        
        // Handle upload gambar
        $gambar = '';
        if (!empty($_FILES['gambar']['name'])) {
            $file = $_FILES['gambar'];
            $filename = time() . '_' . basename($file['name']);
            $upload_path = '../uploads/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $gambar = $filename;
            }
        }
        
        $insert = $conn->query("INSERT INTO produk (kategori_id, nama, deskripsi, harga, stok, gambar) 
                              VALUES ($kategori_id, '$nama', '$deskripsi', $harga, $stok, '$gambar')");
        
        if ($insert) {
            $success = 'Produk berhasil ditambahkan';
        } else {
            $error = 'Gagal menambahkan produk: ' . $conn->error;
        }
    }
}

// Ambil semua produk
$produk_result = $conn->query("SELECT p.*, k.nama as kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Olshop Baju</title>
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
                <h1><i class="fas fa-box"></i> Kelola Produk</h1>
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

            <!-- Form Tambah Produk -->
            <div style="background: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Tambah Produk Baru</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $kat_result = $conn->query("SELECT * FROM kategori");
                            while ($kat = $kat_result->fetch_assoc()):
                            ?>
                                <option value="<?php echo $kat['id']; ?>"><?php echo htmlspecialchars($kat['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="harga">Harga (Rp)</label>
                            <input type="number" id="harga" name="harga" step="1000" required>
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" id="stok" name="stok" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar Produk</label>
                        <input type="file" id="gambar" name="gambar" accept="image/*">
                    </div>

                    <input type="hidden" name="action" value="add_produk">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </button>
                </form>
            </div>

            <!-- Daftar Produk -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 1.5rem;">Daftar Produk</h2>
                
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = $produk_result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <?php if ($row['gambar']): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span style="background: <?php echo $row['stok'] > 0 ? '#d4edda' : '#f8d7da'; ?>; padding: 0.5rem 1rem; border-radius: 5px;">
                                            <?php echo $row['stok']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_produk.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="hapus_produk.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Yakin ingin menghapus?');">
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