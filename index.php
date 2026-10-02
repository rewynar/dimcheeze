<?php
// Hubungkan ke database
require_once 'config/db.php';

// Ambil data produk
$result = $conn->query("SELECT * FROM produk LIMIT 1");
$produk = $result->fetch_assoc();

// Jika harga masih 0 (belum disetting admin), kita anggap Rp 25.000 sebagai contoh sementara
$harga_tampil = ($produk['harga'] > 0) ? $produk['harga'] : 25000;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimcheese - Dimsum Kuah Keju Lumer</title>
    <!-- Font Poppins untuk kesan Modern -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigasi -->
    <nav class="navbar">
        <div class="logo">🧀 Dimcheese</div>
        <ul class="nav-links">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#pesan">Pesan</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>
    </nav>

    <!-- Beranda / Banner -->
    <header id="beranda" class="hero">
        <div class="hero-text">
            <h1>Lumer di Mulut, <br><span>Bikin Nagih Terus!</span></h1>
            <p>Rasakan sensasi dimsum premium dengan siraman kuah keju spesial yang gurih dan creamy.</p>
            <a href="#pesan" class="btn-primary">Pesan Sekarang</a>
        </div>
        <div class="hero-image">
            <!-- Pastikan ada gambar dimsum.jpg di folder assets/img -->
            <img src="<?= $produk['gambar']; ?>" alt="Dimsum Kuah Keju">
        </div>
    </header>

    <!-- Bagian Produk & Checkout -->
    <section id="pesan" class="order-section">
        <div class="product-card">
            <img src="<?= $produk['gambar']; ?>" alt="Dimsum Kuah Keju">
            <div class="product-info">
                <h2><?= $produk['nama']; ?></h2>
                <p><?= $produk['deskripsi']; ?></p>
                <h3 class="price">Rp <span id="harga-satuan"><?= number_format($harga_tampil, 0, ',', '.'); ?></span> / porsi</h3>
            </div>
        </div>

        <div class="checkout-card">
            <h3>Formulir Pemesanan</h3>
            <!-- Form dikirim ke proses_pesanan.php -->
            <form action="proses_pesanan.php" method="POST">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Cth: Maysha">
                </div>
                <div class="input-group">
                    <label>Alamat Pengiriman Lengkap</label>
                    <textarea name="alamat" required placeholder="Cth: Jl. Raya Cikampek No. 1..."></textarea>
                </div>
                <div class="input-group">
                    <label>Catatan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="Cth: Kuah kejunya dipisah ya">
                </div>
                
                <div class="quantity-control">
                    <label>Jumlah Porsi:</label>
                    <div class="counter">
                        <button type="button" id="btn-min">-</button>
                        <input type="number" name="jumlah" id="jumlah-input" value="1" min="1" readonly>
                        <button type="button" id="btn-plus">+</button>
                    </div>
                </div>

                <div class="summary">
                    <p>Total Bayar:</p>
                    <h2 class="total-price">Rp <span id="total-harga"><?= number_format($harga_tampil, 0, ',', '.'); ?></span></h2>
                </div>
                
                <!-- Input hidden untuk kirim data harga ke PHP -->
                <input type="hidden" name="harga_satuan" value="<?= $harga_tampil; ?>">
                <button type="submit" class="btn-whatsapp">🛒 Checkout via WhatsApp</button>
            </form>
        </div>
    </section>

    <!-- Footer & Menu Tersembunyi -->
    <footer id="kontak">
        <p>Ada pertanyaan? Hubungi kami di WhatsApp</p>
        <!-- Menu Admin Tersembunyi diarahkan ke login.php supaya aman -->
        <p>&copy; 2026 Dimcheese. <a href="login.php" style="color: inherit; text-decoration: none; cursor: default;">All rights reserved.</a></p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>