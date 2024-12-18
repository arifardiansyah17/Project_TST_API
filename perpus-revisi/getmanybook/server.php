<?php

require 'PeminjamanService.php';

// Mengatur server SOAP dan lokasi WSDL
$server = new SoapServer('peminjaman_service.wsdl');

// Menetapkan kelas yang akan dipanggil oleh server SOAP
$server->setClass('PeminjamanService');

// Menangani request SOAP
$server->handle();
