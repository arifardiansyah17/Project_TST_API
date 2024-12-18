<?php
require '../wsdl/vendor/autoload.php'; // Memuat autoload Composer
require 'PeminjamanService.php'; // Memuat file kelas

use PHP2WSDL\PHPClass2WSDL;

// Menggunakan generator PHP2WSDL untuk menghasilkan WSDL
$gen = new PHPClass2WSDL('PeminjamanService', 'http://localhost/perpus-revisi/getmanybook/peminjaman_service.wsdl');

// Generate WSDL dan simpan ke file
$gen->generateWSDL();
file_put_contents("peminjaman_service.wsdl", $gen->dump());

echo "WSDL berhasil dibuat!";
?>
