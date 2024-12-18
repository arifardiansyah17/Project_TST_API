<?php

require 'LibraryNotification.php';

// Mengatur server SOAP dan lokasi WSDL
$server = new SoapServer('library_notification.wsdl');

// Menetapkan kelas yang akan dipanggil oleh server SOAP
$server->setClass('LibraryNotification');

// Menangani request SOAP
$server->handle();
