<?php
session_start();
include('config.php');

// Cek apakah pengguna adalah mahasiswa
if (!isset($_SESSION['nim']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil data peminjaman buku berdasarkan NIM mahasiswa
$nim = $_SESSION['nim'];
$query = "SELECT * FROM peminjaman WHERE nim = '$nim'";
$result = $conn->query($query);
$peminjamanBuku = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku</title>
    <style>
        /* Styling untuk tabel dan halaman peminjaman */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .status-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .status-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Peminjaman Buku</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($peminjamanBuku) > 0) { ?>
                    <?php foreach ($peminjamanBuku as $index => $buku) { ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $buku['judul_buku']; ?></td>
                            <td><?php echo $buku['tanggal_peminjaman']; ?></td>
                            <td><?php echo $buku['tanggal_pengembalian']; ?></td>
                            <td>
                                <!-- Tombol untuk melihat status pengembalian -->
                                <button class="status-btn" onclick="showStatus('<?php echo $buku['tanggal_pengembalian']; ?>')">Lihat Status</button>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="5">Tidak ada buku yang dipinjam.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Popup untuk status notifikasi -->
    <div id="statusPopup" style="display:none;">
        <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;">
            <div style="background-color: white; padding: 20px; max-width: 400px; margin: 100px auto; border-radius: 8px; text-align: center;">
                <h3>Status Pengembalian</h3>
                <p id="statusMessage"></p>
                <button onclick="closePopup()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function showStatus(tanggalPengembalian) {
            fetch('rest.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `nim=<?php echo $nim; ?>&tanggal_pengembalian=${tanggalPengembalian}`
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('statusMessage').textContent = data.status;
                document.getElementById('statusPopup').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('statusMessage').textContent = "Terjadi kesalahan. Coba lagi.";
                document.getElementById('statusPopup').style.display = 'block';
            });
        }

        // Fungsi untuk menutup popup
        function closePopup() {
            document.getElementById('statusPopup').style.display = 'none';
        }
    </script>
</body>
</html>
