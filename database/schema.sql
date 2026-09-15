-- Database untuk Olshop Baju
CREATE DATABASE IF NOT EXISTS olshop_baju;
USE olshop_baju;

-- Tabel Kategori
CREATE TABLE kategori (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Produk (Baju)
CREATE TABLE produk (
  id INT PRIMARY KEY AUTO_INCREMENT,
  kategori_id INT NOT NULL,
  nama VARCHAR(150) NOT NULL,
  deskripsi TEXT,
  harga DECIMAL(10, 2) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  gambar VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
);

-- Tabel Pengguna
CREATE TABLE pengguna (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  no_telepon VARCHAR(15),
  alamat TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Keranjang
CREATE TABLE keranjang (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pengguna_id INT NOT NULL,
  produk_id INT NOT NULL,
  jumlah INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- Tabel Pesanan
CREATE TABLE pesanan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pengguna_id INT NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'diproses', 'dikirim', 'selesai') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- Tabel Detail Pesanan
CREATE TABLE detail_pesanan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pesanan_id INT NOT NULL,
  produk_id INT NOT NULL,
  jumlah INT NOT NULL,
  harga DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- Tabel Admin
CREATE TABLE admin (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Sampel Kategori
INSERT INTO kategori (nama, deskripsi) VALUES
('Kaos', 'Kaos pria dan wanita berbagai warna'),
('Kemeja', 'Kemeja formal dan casual'),
('Dress', 'Dress wanita untuk berbagai acara'),
('Celana', 'Celana panjang dan pendek'),
('Jaket', 'Jaket untuk berbagai musim');

-- Data Sampel Produk
INSERT INTO produk (kategori_id, nama, deskripsi, harga, stok, gambar) VALUES
(1, 'Kaos Polos Putih', 'Kaos polos premium 100% katun', 50000, 20, 'kaos-putih.jpg'),
(1, 'Kaos Polos Hitam', 'Kaos polos premium 100% katun', 50000, 15, 'kaos-hitam.jpg'),
(2, 'Kemeja Formal Biru', 'Kemeja formal untuk kantor', 150000, 10, 'kemeja-biru.jpg'),
(2, 'Kemeja Casual Coklat', 'Kemeja casual untuk santai', 120000, 8, 'kemeja-coklat.jpg'),
(3, 'Dress Pesta Merah', 'Dress elegan untuk acara pesta', 200000, 5, 'dress-merah.jpg'),
(4, 'Celana Jeans Panjang', 'Celana jeans dengan kualitas baik', 180000, 12, 'jeans-panjang.jpg'),
(5, 'Jaket Kulit Hitam', 'Jaket kulit asli warna hitam', 500000, 3, 'jaket-kulit.jpg');

-- Admin Sampel (password: admin123)
INSERT INTO admin (username, password, email) VALUES
('admin', '$2y$10$YQv8rL5Z.0p5H0z5QzZ5eOU9dL0yzF6Z0H0z5QzZ5eO9dL0yzF6Z2', 'admin@olshop.com');
