<?php
// config/db.php

$host = "localhost";
$user = "root"; // Ubah ini nanti kalau sudah di InfinityFree
$pass = "";     // Ubah ini nanti kalau sudah di InfinityFree
$db   = "db_dimcheese"; // Nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>