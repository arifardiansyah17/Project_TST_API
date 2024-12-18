<?php


class PeminjamanService {


    public function getStudentLoanInfo($nim) {
        if (empty($nim)) {
            throw new SoapFault('Client', 'Parameter NIM tidak boleh kosong.');
        }

       
        // Tentukan batas peminjaman berdasarkan status
        $maxBorrowableBooks = ($status === 'Aktif') ? 10 : 
                               (in_array($status, ['Nonaktif', 'Cuti']) ? 3 : 0);

        // Buat pesan string
        $message = "Mahasiswa dengan NIM $nim memiliki status $status dan berhak meminjam $maxBorrowableBooks buku.";

        return $message;
    }
}

// Konfigurasi SOAP server
$server = new SoapServer(null, [
    'uri' => 'http://localhost/perpus-revisi/getmanybook',
]);

$server->setClass('PeminjamanService');
$server->handle();
