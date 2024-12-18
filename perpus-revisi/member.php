<?php
session_start();
if (!isset($_SESSION['nim']) && !isset($_SESSION['password'])) {
    header('Location: login.php');
    exit;
}

$nim = $_SESSION['nim'];
$nama = $_SESSION['nama'];
$status = $_SESSION['status'];

include 'config.php';

// Function to borrow a book
if (isset($_GET['borrow'])) {
    $bookId = $_GET['borrow'];
    
    // Check if the book is available
    $stmt = $pdo->prepare("SELECT is_borrowed FROM books WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if ($book && !$book['is_borrowed']) {
        // Get the student's status from the session
        $status = $_SESSION['status'];
    
        // Determine the return date based on the status
        if ($status === 'Aktif') {
            $returnDate = date('Y-m-d', strtotime('+21 days'));  // 21 days from borrow date
        } else {
            $returnDate = date('Y-m-d', strtotime('+14 days'));  // 14 days from borrow date
        }
    
        // Get today's date for borrow date
        $borrowDate = date('Y-m-d');  
    
        // Update the book status to borrowed
        $stmt = $pdo->prepare("UPDATE books SET is_borrowed = TRUE, borrower_id = ?, borrow_date = ?, return_date = ? WHERE id = ?");
        $stmt->execute([$nim, $borrowDate, $returnDate, $bookId]);
    
        // Redirect back to the member page or another appropriate page
        header('Location: member.php');
        exit;
    } else {
        echo "<p>This book is already borrowed.</p>";
    }
}    

// Fetch all books to display
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member - Daftar Buku</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 50px;
        }

        /* Header section */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        /* Member info section */
        .member-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .logout-button:hover {
            background-color: #e53935;
        }

        /* Table styling */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Button styling */
        .borrow-button {
            display: inline-block;
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .borrow-button:hover {
            background-color: #45a049;
        }

        .borrow-button:disabled {
            background-color: #d3d3d3;
            cursor: not-allowed;
        }
    </style>
     <!-- Include jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Include your custom client.js script -->
    <script src="client.js"></script>
</head>
<body>
    <input type="hidden" id="loggedNim" value="<?= htmlspecialchars($nim) ?>">
    <input type="hidden" id="loggedStatus" value="<?= htmlspecialchars($status) ?>">

    <div class="container">
        <div class="member-info">
            <h2>Welcome, <?= htmlspecialchars($nama) ?>!</h2>
            <p>NIM: <?= htmlspecialchars($nim) ?></p>
            <p>Status: <?= htmlspecialchars($status) ?></p>
            <div id="result"></div>
        </div>

        <a href="logout.php" class="logout-button">Logout</a>

        <h2>Daftar Buku</h2>

        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= $book['is_borrowed'] ? 'Dipinjam' : 'Tersedia' ?></td>
                        <td><?= $book['borrow_date'] ? htmlspecialchars($book['borrow_date']) : '-' ?></td>
                        <td><?= $book['return_date'] ? htmlspecialchars($book['return_date']) : '-' ?></td>
                        <td>
                            <?php if (!$book['is_borrowed']): ?>
                                <a href="member.php?borrow=<?= $book['id'] ?>" class="borrow-button">Pinjam</a>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
