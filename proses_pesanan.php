<?php
// Hubungkan ke database
require_once 'config/db.php';

// Pastikan file ini diakses dari form submit, bukan dibuka langsung
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form dan amankan dari karakter berbahaya
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $catatan = $conn->real_escape_string($_POST['catatan']);
    
    // Ambil angka jumlah & harga
    $jumlah = (int)$_POST['jumlah'];
    $harga_satuan = (int)$_POST['harga_satuan'];
    
    // Hitung total harga
    $total_harga = $jumlah * $harga_satuan;

    // 1. SIMPAN PESANAN KE DATABASE
    $sql = "INSERT INTO pesanan (nama_pembeli, alamat, catatan, jumlah, total_harga) 
            VALUES ('$nama', '$alamat', '$catatan', $jumlah, $total_harga)";
    
    if ($conn->query($sql) === TRUE) {
        
        // 2. SIAPKAN PESAN WHATSAPP
        // PENTING: Ganti nomor di bawah ini dengan nomor WA penjual yang asli
        // Formatnya gunakan awalan 62 (contoh: 6281234567890) tanpa angka 0 di depan
        $no_wa = "6281293013716"; 
        
        // Format harga jadi Rupiah rapi (contoh: 25.000)
        $harga_format = number_format($harga_satuan, 0, ',', '.');
        $total_format = number_format($total_harga, 0, ',', '.');
        
        // Kalau catatan kosong, ubah jadi tanda strip "-"
        $teks_catatan = empty($catatan) ? "-" : $catatan;

        // Rakit teks pesan sesuai konsepmu (menggunakan \n untuk baris baru / enter)
        $pesan = "Halo, Dimcheese! Saya ingin memesan Dimsum Kuah Keju.\n\n";
        $pesan .= "🛒 *DETAIL PESANAN*\n";
        $pesan .= "Produk: Dimsum Kuah Keju\n";
        $pesan .= "Jumlah: $jumlah porsi\n";
        $pesan .= "Harga: Rp$harga_format\n";
        $pesan .= "Total: *Rp$total_format*\n\n";
        $pesan .= "👤 *DATA PEMBELI*\n";
        $pesan .= "Nama: $nama\n";
        $pesan .= "Alamat: $alamat\n\n";
        $pesan .= "📝 *CATATAN*\n";
        $pesan .= "$teks_catatan\n\n";
        $pesan .= "Mohon konfirmasi pesanan dan informasi pembayaran. Terima kasih!";

        // Ubah teks pesan menjadi format yang bisa dibaca oleh URL browser
        $pesan_encode = urlencode($pesan);

        // 3. ARAHKAN OTOMATIS KE WHATSAPP
        header("Location: https://wa.me/$no_wa?text=$pesan_encode");
        exit();
        
    } else {
        // Jika gagal menyimpan ke database, tampilkan error
        echo "Waduh, ada yang salah saat menyimpan pesanan: " . $conn->error;
    }
} else {
    // Kalau ada orang usil langsung buka proses_pesanan.php, kembalikan ke beranda
    header("Location: index.php");
    exit();
}
?>