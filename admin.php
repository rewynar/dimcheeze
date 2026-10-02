<?php
session_start();

// Cek apakah user sudah login, jika belum lempar kembali ke login.php
if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header("Location: login.php");
    exit();
}

// Hubungkan ke database
require_once 'config/db.php';

// 1. PROSES UPDATE PRODUK
if (isset($_POST['update_produk'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $harga = (int)$_POST['harga'];

    $sql_update = "UPDATE produk SET nama='$nama', deskripsi='$deskripsi', harga=$harga WHERE id=1";
    if ($conn->query($sql_update)) {
        $pesan_sukses = "Data produk berhasil diperbarui!";
    }
}

// 2. PROSES UPDATE STATUS PESANAN
if (isset($_POST['update_status'])) {
    $id_pesanan = (int)$_POST['id_pesanan'];
    $status_baru = $conn->real_escape_string($_POST['status']);

    $sql_status = "UPDATE pesanan SET status='$status_baru' WHERE id=$id_pesanan";
    $conn->query($sql_status);
}

// 3. LOGIKA FILTER PESANAN
$where_clause = "";
$filter_aktif = "Semua";
if (isset($_GET['filter_status']) && $_GET['filter_status'] != 'Semua') {
    $filter_aktif = $conn->real_escape_string($_GET['filter_status']);
    $where_clause = "WHERE status = '$filter_aktif'";
}

// Ambil data produk saat ini
$result_produk = $conn->query("SELECT * FROM produk LIMIT 1");
$produk = $result_produk->fetch_assoc();

// Ambil semua data pesanan sesuai filter, urutkan dari yang terbaru
$result_pesanan = $conn->query("SELECT * FROM pesanan $where_clause ORDER BY waktu_pesan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Dimcheese</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FFC107; --bg: #F4F7F6; --dark: #333; --white: #FFF;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); color: var(--dark); padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        /* Tombol & Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; gap: 10px; }
        .btn-group { display: flex; gap: 10px; }
        .btn-kembali { text-decoration: none; background: var(--dark); color: var(--white); padding: 10px 20px; border-radius: 5px; }
        .btn-logout { background: #dc3545; }
        
        .card { background: var(--white); padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        h2 { margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 10px; display: inline-block; }
        
        /* Form & Input */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #CCC; border-radius: 5px; }
        .btn-simpan { background: var(--primary); color: var(--dark); border: none; padding: 10px 20px; font-weight: bold; cursor: pointer; border-radius: 5px; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        
        /* Bagian Filter */
        .filter-container { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .filter-container select { padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-filter { background: var(--dark); color: var(--white); padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; }

        /* Warna Status */
        .status-selesai { color: #28a745; font-weight: 700; }
        .status-batal { color: #dc3545; font-weight: 700; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #DDD; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: var(--primary); color: var(--dark); }
        select { padding: 5px; border-radius: 3px; }
        .btn-update-status { background: var(--dark); color: var(--white); border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; font-size: 12px; }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            table { display: block; overflow-x: auto; white-space: nowrap; }
            .header { flex-direction: column; align-items: flex-start; }
            .btn-group { margin-top: 10px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>⚙️ Dashboard Admin</h1>
        <div class="btn-group">
            <a href="index.php" class="btn-kembali" target="_blank">Lihat Website</a>
            <a href="logout.php" class="btn-kembali btn-logout">Logout</a>
        </div>
    </div>

    <?php if(isset($pesan_sukses)) echo "<div class='alert'>$pesan_sukses</div>"; ?>

    <!-- PENGATURAN PRODUK -->
    <div class="card">
        <h2>Pengaturan Produk</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" value="<?= $produk['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Satuan (Rp)</label>
                <input type="number" name="harga" value="<?= $produk['harga']; ?>" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" required><?= $produk['deskripsi']; ?></textarea>
            </div>
            <button type="submit" name="update_produk" class="btn-simpan">💾 Simpan Perubahan</button>
        </form>
    </div>

    <!-- DAFTAR PESANAN -->
    <div class="card">
        <h2>Daftar Pesanan Masuk</h2>
        
        <!-- Form Filter -->
        <form method="GET" class="filter-container">
            <label><strong>Filter:</strong></label>
            <select name="filter_status">
                <option value="Semua" <?= $filter_aktif == 'Semua' ? 'selected' : ''; ?>>Tampilkan Semua</option>
                <option value="Menunggu Pembayaran" <?= $filter_aktif == 'Menunggu Pembayaran' ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                <option value="Diproses" <?= $filter_aktif == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                <option value="Selesai" <?= $filter_aktif == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                <option value="Batal" <?= $filter_aktif == 'Batal' ? 'selected' : ''; ?>>Batal</option>
            </select>
            <button type="submit" class="btn-filter">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result_pesanan->fetch_assoc()): 
                    // Menentukan warna tulisan status
                    $warna_status = "";
                    if ($row['status'] == "Selesai") {
                        $warna_status = "status-selesai"; // Hijau
                    } elseif ($row['status'] == "Batal") {
                        $warna_status = "status-batal"; // Merah
                    }
                ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($row['waktu_pesan'])); ?></td>
                    <td>
                        <strong><?= $row['nama_pembeli']; ?></strong><br>
                        <small><?= $row['alamat']; ?></small>
                    </td>
                    <td>
                        <?= $row['jumlah']; ?> Porsi<br>
                        <small><em>Catatan: <?= empty($row['catatan']) ? '-' : $row['catatan']; ?></em></small>
                    </td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    
                    <!-- Menampilkan Status dengan Warna -->
                    <td class="<?= $warna_status; ?>">
                        <?= $row['status']; ?>
                    </td>
                    
                    <td>
                        <!-- Form untuk ubah status -->
                        <form method="POST" style="display:flex; gap:5px; flex-direction:column;">
                            <input type="hidden" name="id_pesanan" value="<?= $row['id']; ?>">
                            <select name="status">
                                <option value="Menunggu Pembayaran" <?= $row['status'] == 'Menunggu Pembayaran' ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                                <option value="Diproses" <?= $row['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : ''; ?>>Batal</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-update-status">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if($result_pesanan->num_rows == 0): ?>
                    <tr><td colspan="6" style="text-align:center;">Tidak ada pesanan ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>