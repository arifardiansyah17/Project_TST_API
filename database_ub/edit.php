<?php
include 'index.php';

if (isset($_GET['nim'])) {
    $id = $_GET['nim'];
    $result = $conn->query("SELECT * FROM mahasiswa WHERE nim=$nim");
    $row = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $semester = $_POST['semester'];
    $status = $_POST['status'];

    $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', semester='$semester', status='$status' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Mahasiswa</h2>
        <form method="POST" action="">
            <input type="text" name="nim" value="<?php echo $row['nim']; ?>" required>
            <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required>
            <input type="number" name="semester" value="<?php echo $row['semester']; ?>" required>
            <select name="status">
                <option value="Aktif" <?php if ($row['status'] == 'Aktif') echo 'selected'; ?>>Aktif</option>
                <option value="Nonaktif" <?php if ($row['status'] == 'Nonaktif') echo 'selected'; ?>>Nonaktif</option>
            </select>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
