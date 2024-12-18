<?php
session_start();
if (!isset($_SESSION['nim']) || $_SESSION['status'] !== 'admin') {
    header('Location: login.php');
    exit;
}
include 'config.php';

// Fungsi untuk menambahkan buku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $stmt = $pdo->prepare("INSERT INTO books (title, author, is_borrowed) VALUES (?, ?, FALSE)");
    $stmt->execute([$title, $author]);
    header('Location: admin.php');
    exit;
}

// Fungsi untuk menghapus buku
if (isset($_GET['delete'])) {
    $bookId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$bookId]);
    header('Location: admin.php');
    exit;
}

// Fungsi untuk mengembalikan buku
if (isset($_GET['return'])) {
    $bookId = $_GET['return'];
    // Update buku agar tersedia kembali dan hapus tanggal peminjaman dan pengembalian serta id peminjam
    $stmt = $pdo->prepare("UPDATE books SET is_borrowed = FALSE, borrower_id = NULL, borrow_date = NULL, return_date = NULL WHERE id = ?");
    $stmt->execute([$bookId]);
    header('Location: admin.php');
    exit;
}

// Mengambil semua buku beserta data peminjam (jika ada)
$stmt = $pdo->query("
    SELECT books.*, users.username AS borrower 
    FROM books 
    LEFT JOIN users ON books.borrower_id = users.id
");
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manajemen Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Manajemen Buku</h2>
    <a href="logout.php" class="logout-button">Logout</a>
    <form method="POST">
        <input type="text" name="title" placeholder="Judul Buku" required>
        <input type="text" name="author" placeholder="Penulis" required>
        <button type="submit" name="add">Tambah Buku</button>
    </form>

    <table border="1">
        <tr>
            <th>Judul Buku</th>
            <th>Penulis</th>
            <th>Status</th>
            <th>Peminjam</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Aksi</th>
            <th>Hapus Buku</th>
        </tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['title'] ?? '') ?></td>
            <td><?= htmlspecialchars($book['author'] ?? '') ?></td>
            <td><?= $book['is_borrowed'] ? 'Dipinjam' : 'Tersedia' ?></td>
            <td><?= $book['is_borrowed'] ? htmlspecialchars($book['borrower_id'] ?? '-') : '-' ?></td>
            <td><?= $book['borrow_date'] ? htmlspecialchars($book['borrow_date'] ?? '-') : '-' ?></td>
            <td><?= $book['return_date'] ? htmlspecialchars($book['return_date'] ?? '-') : '-' ?></td>
            <td>
                <?php if ($book['is_borrowed']): ?>
                    <a href="admin.php?return=<?= $book['id'] ?>" class="return-button">Kembalikan</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><a href="admin.php?delete=<?= $book['id'] ?>" class="logout-button">Hapus</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
