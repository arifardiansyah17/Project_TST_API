<?php
$servername = "localhost:3306"; // atau "localhost:3307" jika menggunakan port 3307
$username = "root";
$password = ""; // kosong jika tidak ada password default
$database = "sistem_mahasiswa";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
