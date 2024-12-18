<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Mendapatkan nilai dari form
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Membuat URL API dengan parameter NIM dan Password
    $api_url = "http://localhost/restapi-project/getdatamahasiswa.php?nim=" . urlencode($nim) . "&password=" . urlencode($password);

    // Mengambil respons dari API
    $response = file_get_contents($api_url);

    // Mengecek apakah respons valid
    if ($response === FALSE) {
        die('Error occurred while fetching data.');
    }

    // Mengubah respons JSON menjadi array PHP
    $data = json_decode($response, true);

    // Mengecek jika data berhasil didecode dan status berhasil
    // Mengecek jika data berhasil didecode dan status berhasil
    if ($data && $data['status']['code'] === 200) {
        // Simpan data pengguna ke dalam session
        $_SESSION['nim'] = $data['results']['nim'];
        $_SESSION['nama'] = $data['results']['nama'];
        $_SESSION['status'] = $data['results']['status'];

        // Redirect berdasarkan role (misalnya status mahasiswa atau lainnya)
        if ($data['results']['status'] === 'Aktif') {
            header('Location: member.php'); // Pengguna yang aktif dialihkan ke member.php
        } else if($data['results']['status'] === 'Nonaktif'){ 
            header('Location: member.php');
        }else {
            header('Location: admin.php'); // Pengguna yang tidak aktif dialihkan ke halaman tertentu
        }
        exit;
    } else {
        $error = 'Invalid NIM or Password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa</title>
    <style>
        /* Reset CSS untuk menghilangkan margin dan padding default */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Gaya untuk body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Gaya untuk form login */
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 600px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Gaya input field */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Gaya tombol submit */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Gaya pesan error */
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        /* Gaya untuk label */
        label {
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Selamat Datang di Perpustakaan Indonesia</h2>
        
        <!-- Form Login -->
        <form method="POST" action="">
            <label for="nim">Username</label><br>
            <input type="text" id="nim" name="nim" required><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Login">
        </form>

        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>

</body>
</html>
