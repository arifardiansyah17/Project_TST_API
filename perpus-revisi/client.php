<?php


$wsdl = "http://localhost/perpus-revisi/getmanybook/peminjaman_service.wsdl";

try {
    // Membuat klien SOAP
    $client = new SoapClient($wsdl);
    
    $nim = $_POST['nim'];
    $status = $_POST['status'];
    
    // Gunakan metode getStudentLoanInfo untuk mendapatkan informasi lengkap
    $response = $client->getStudentLoanInfo($nim, $status);

header('Content-Type: application/json');
echo json_encode(['message' => $response]);
} catch (Exception $e) {
// Tangani error SOAP
http_response_code(500);
echo json_encode(['message' => $e->getMessage()]);
}