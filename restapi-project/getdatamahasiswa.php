<?php

// Header hasil JSON
header("Content-Type: application/json; charset=UTF-8");

// Tangkap method akses
$method = $_SERVER['REQUEST_METHOD'];

// Variabel hasil
$result = array();

// Fungsi koneksi database
function getDatabaseConnection() {
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $database = "sistem_mahasiswa";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die(json_encode([
            "status" => [
                "code" => 500,
                "description" => "Database Connection Failed: " . $conn->connect_error
            ]
        ]));
    }
    return $conn;
}

// Fungsi mendapatkan data mahasiswa berdasarkan NIM dan Password
function getUserData($conn, $nim, $password) {
    $sql = "SELECT * FROM users WHERE nim = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nim, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fungsi mendapatkan data mahasiswa berdasarkan NIM
function getMahasiswaData($conn, $nim) {
    $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Cek method
if ($method == 'GET') {

    if (isset($_GET['nim'], $_GET['password'])) {
        $nim = $_GET['nim'];
        $password = $_GET['password'];

        // Koneksi database
        $conn = getDatabaseConnection();

        // Validasi user login
        $user = getUserData($conn, $nim, $password);

        if ($user) {
            // Ambil data mahasiswa
            $mahasiswa = getMahasiswaData($conn, $nim);

            if ($mahasiswa) {
                // Tentukan lama peminjaman berdasarkan status (case-insensitive)
                if (strtolower($mahasiswa['status']) == 'aktif') {
                    $lamaPeminjaman = 21;
                } else {
                    $lamaPeminjaman = 14;
                }

                $result['status'] = [
                    "code" => 200,
                    "description" => "Login Successful"
                ];
                $result['results'] = [
                    "nim" => $mahasiswa['nim'],
                    "nama" => $mahasiswa['nama'],
                    "status" => $mahasiswa['status'],
                    "lama_peminjaman" => $lamaPeminjaman . " hari"
                ];
            } else {
                $result['status'] = [
                    "code" => 404,
                    "description" => "Mahasiswa Not Found"
                ];
            }
        } else {
            $result['status'] = [
                "code" => 401,
                "description" => "Invalid NIM or Password"
            ];
        }

        $conn->close();
    } else {
        $result['status'] = [
            "code" => 400,
            "description" => "Invalid Parameters: NIM and Password are required"
        ];
    }
} else {
    $result['status'] = [
        "code" => 405,
        "description" => "Method Not Allowed"
    ];
}

// Outputkan hasil
echo json_encode($result);

?>
