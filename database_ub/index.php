<?php

session_start();
if (!isset($_SESSION['nim']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Menambah Data Mahasiswa
if (isset($_POST['add'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $semester = $_POST['semester'];
    $status = $_POST['status'];

    $sql = "INSERT INTO mahasiswa (nim, nama, semester, status) VALUES ('$nim', '$nama', '$semester', '$status')";
    $conn->query($sql);
}

// Menghapus Data Mahasiswa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM mahasiswa WHERE id=$id";
    $conn->query($sql);
}

// Menampilkan Data Mahasiswa
$result = $conn->query("SELECT * FROM mahasiswa");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Database Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Data Mahasiswa</h2>
        <form method="POST" action="">
            <input type="text" name="nim" placeholder="NIM" required>
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="number" name="semester" placeholder="Semester" required>
            <select name="status">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
            <button type="submit" name="add">Tambah Mahasiswa</button>
        </form>

        <table>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Semester</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nim']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['semester']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
