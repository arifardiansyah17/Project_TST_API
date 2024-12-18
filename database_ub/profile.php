<?php
session_start();
include('config.php');

// Cek apakah pengguna adalah mahasiswa
if (!isset($_SESSION['nim']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil data mahasiswa berdasarkan NIM
$nim = $_SESSION['nim'];
$query = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
$result = $conn->query($query);
$mahasiswa = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mahasiswa</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            flex-direction: column;
        }

        .profile-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            z-index: 1;
            margin-top: 10px; /* Membuat jarak antara notifikasi dan konten profil */
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 1em;
            color: #555;
            margin: 8px 0;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        /* Styling for the labels */
        strong {
            color: #333;
            font-weight: bold;
        }

        /* Styling for the notification box */
        #notificationBox {
            position: fixed;
            top: 80px; /* Menyisipkan sedikit jarak dari atas */
            left: 50%;
            transform: translateX(-50%); /* Menempatkan box di tengah layar */
            padding: 15px;
            background-color: #ffcc00;
            color: #333;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            display: none;
            z-index: 9999; /* Memastikan box notifikasi berada di atas konten lainnya */
            width: auto;
            max-width: 300px; /* Membatasi lebar maksimal box */
        }

        .notification-box {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f55757;
            font-family: Arial, sans-serif;
            transition: transform 0.3s ease;
        }

        /* Efek hover untuk membuat kartu sedikit bergerak */
        .notification-box:hover {
            transform: translateY(-5px);
        }

        /* Status sukses */
        .notification-box.success {
            border: 2px solid #4caf50;
            background-color: #e8f5e9;
            color: #388e3c;
        }

        /* Status error */
        .notification-box.error {
            border: 2px solid #f44336;
            background-color: #ffebee;
            color: #d32f2f;
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="client.js"></script>
</head>
<body>

    <!-- Simpan NIM ke dalam elemen tersembunyi -->
    <input type="hidden" id="loggedNim" value="<?php echo $mahasiswa['nim']; ?>">

    <!-- Tempat notifikasi akan ditampilkan -->
    <div id="result"></div>

    <div class="profile-container">
        <h2>Profil Mahasiswa</h2>
        <?php if ($mahasiswa) { ?>
            <p><strong>NIM:</strong> <?php echo $mahasiswa['nim']; ?></p>
            <p><strong>Nama:</strong> <?php echo $mahasiswa['nama']; ?></p>
            <p><strong>Semester:</strong> <?php echo $mahasiswa['semester']; ?></p>
            <p><strong>Status:</strong> <?php echo $mahasiswa['status']; ?></p>
        <?php } else { ?>
            <p>Data mahasiswa tidak ditemukan.</p>
        <?php } ?>

        <a href="login.php">Logout</a>

    </div>


</body>
</html>
