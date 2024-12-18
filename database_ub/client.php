<?php
    // URL WSDL dari server SOAP
    $wsdl = "http://localhost/perpus-revisi/wsdl/library_notification.wsdl";

    try {
        // Inisialisasi client SOAP
        $client = new SoapClient($wsdl);

        // Ambil data dari request (nim)
        $nim = $_POST['nim'];

        // Panggil metode getBooksByNIM dengan nim sebagai parameter
        $response = $client->getBooksByNIM($nim);

         // Kirimkan respons kembali ke client dalam format JSON
    header('Content-Type: application/json');
    echo json_encode(['message' => $response]);
} catch (Exception $e) {
    // Tangani error SOAP
    http_response_code(500);
    echo json_encode(['message' => $e->getMessage()]);
}
