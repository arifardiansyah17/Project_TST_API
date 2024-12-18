<?php
header("Content-Type: application/json");
include('config.php');

// Cek apakah request method adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    // Ambil data dari POST
    $nim = $_POST['nim'];
    $tanggalPengembalian = $_POST['tanggal_pengembalian'];

    // Query untuk mengambil tanggal peminjaman berdasarkan NIM dan tanggal pengembalian
    $query = "SELECT tanggal_peminjaman FROM peminjaman WHERE nim = ? AND tanggal_pengembalian = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nim, $tanggalPengembalian);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah data ditemukan
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $tanggalPeminjaman = $data['tanggal_peminjaman'];

        $status = "";

        // Cek kondisi status pengembalian buku
        if ($tanggalPengembalian === $tanggalPeminjaman) {
            $status = "Hari ini adalah tenggat pengembalian buku. Harap segera kembalikan.";
        } elseif (strtotime($tanggalPeminjaman) > strtotime($tanggalPengembalian)) {
            $status = "Tenggat pengembalian buku telah habis. Mohon kembalikan buku secepatnya.";
        } else {
            $status = "Buku masih dalam masa pinjaman.";
        }

        echo json_encode(["status" => $status]);
    } else {
        // Jika data tidak ditemukan
        echo json_encode(["status" => "Data tidak ditemukan untuk NIM atau tanggal pengembalian tersebut."]);
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    // Jika method bukan POST
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>
