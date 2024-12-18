<?php

class LibraryNotification {
    private $pdoLibrary;
    private $pdoMahasiswa;

    // Constructor untuk inisialisasi koneksi database
    public function __construct() {
        $this->pdoLibrary = new PDO('mysql:host=localhost;dbname=library', 'root', '');
        $this->pdoMahasiswa = new PDO('mysql:host=localhost;dbname=sistem_mahasiswa', 'root', '');
    }

    /**
     * Menampilkan buku yang dipinjam berdasarkan NIM dan memiliki tanggal pengembalian mendekati atau telah lewat.
     * @param string $nim
     * @return string
     */
    public function getBooksByNIM($nim) {
        // Validasi apakah mahasiswa ada di sistem_mahasiswa
        $stmt = $this->pdoMahasiswa->prepare('SELECT * FROM mahasiswa WHERE nim = :nim');
        $stmt->execute(['nim' => $nim]);
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$mahasiswa) {
            return "Mahasiswa tidak ditemukan";
        }
    
        // Ambil buku yang dipinjam oleh mahasiswa berdasarkan borrower_id
        $stmt = $this->pdoLibrary->prepare('SELECT * FROM books WHERE borrower_id = :nim AND return_date IS NOT NULL');
        $stmt->execute(['nim' => $nim]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($books)) {
            return "Tidak ada buku yang dipinjam";
        }
    
        // Tentukan tanggal hari ini
        $today = new DateTime();
    
        // Filter buku yang memiliki tanggal pengembalian mendekati hari ini atau sudah lewat
        $dueBooks = [];
        foreach ($books as $book) {
            $returnDate = new DateTime($book['return_date']);
            $interval = $today->diff($returnDate);
            $daysDifference = (int) $interval->format('%r%a'); // Selisih hari
    
            if ($daysDifference <= 0 || ($daysDifference <= 5 && $daysDifference > 0)) {
                $dueBooks[] = [
                    'id' => $book['id'],
                    'title' => $book['title'],
                    'author' => $book['author'],
                    'return_date' => $book['return_date'],
                    'days_left' => $daysDifference
                ];
            }
        }
    
        if (empty($dueBooks)) {
            return "Tidak ada buku yang mendekati tenggat waktu atau sudah jatuh tempo";
        }
    
        // Mengurutkan buku berdasarkan tanggal pengembalian (terdekat dengan hari ini)
        usort($dueBooks, function($a, $b) {
            return $a['days_left'] <=> $b['days_left'];
        });
    
        // Ambil buku yang paling mendekati atau sudah jatuh tempo
        $book = $dueBooks[0];
    
        // Persiapkan pesan notifikasi
        if ($book['days_left'] >= 0) {
            return "Pesan dari Perpustakaan Brawijaya\n\nPemberitahuan : Batas waktu peminjaman buku Anda akan berakhir dalam " . $book['days_left'] . " hari. Kami mohon Anda segera mengembalikan buku berjudul '" . $book['title'] . "' ke Perpustakaan Brawijaya sebelum tanggal jatuh tempo. Terima kasih atas perhatian dan kerja sama Anda. \nMari terus memperluas wawasan melalui membaca, karena membaca adalah jendela dunia.";
        } else {
            return "Pesan dari Perpustakaan Brawijaya\nPemberitahuan : Buku yang Anda pinjam telah melewati batas waktu peminjaman selama " . abs($book['days_left']) . " hari. Kami mohon Anda segera mengembalikan buku berjudul '" . $book['title'] . "' ke Perpustakaan Brawijaya.\nTerimakasih sudah membaca, membaca adalah investasi terbaik untuk masa depan.";
        }
    }
}    

