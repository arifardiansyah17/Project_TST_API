<?php
require "vendor/autoload.php";
require "LibraryNotification.php";

// Membuat objek PHP2WSDL untuk menghasilkan WSDL dari kelas LibraryNotification
$gen = new \PHP2WSDL\PHPClass2WSDL("LibraryNotification", "http://localhost/perpus-revisi/wsdl/server.php");
$gen->generateWSDL();

// Menyimpan file WSDL
file_put_contents("library_notification.wsdl", $gen->dump());
echo "WSDL file generated successfully!";
