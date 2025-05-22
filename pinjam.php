<?php
session_start();
include("koneksi.php");

$message = "";
$book_data = null; // Inisialisasi book_data

if (isset($_GET['kode_buku'])) {
    $kode_buku = mysqli_real_escape_string($koneksi, $_GET['kode_buku']);

    // Ambil data buku untuk ditampilkan dan cek stok
    $sql_book = "SELECT * FROM data_buku WHERE kode_buku = '$kode_buku'";
    $result_book = mysqli_query($koneksi, $sql_book);
    $book_data = mysqli_fetch_array($result_book);

    if (!$book_data) {
        $message = "Buku tidak ditemukan.";
    }

    if (isset($_POST['submit_pinjam'])) {
        if ($book_data) { // Pastikan data buku ditemukan sebelum melanjutkan
            $nama_peminjam = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']);
            $tanggal_pinjam = date('Y-m-d');
            $tanggal_kembali_target = date('Y-m-d', strtotime('+7 days')); // Ini tanggal target, bukan aktual

            // Ambil stok terbaru sebelum mencoba meminjam
            $current_stok_sql = "SELECT stok FROM data_buku WHERE kode_buku = '$kode_buku'";
            $current_stok_result = mysqli_query($koneksi, $current_stok_sql);
            $current_stok_row = mysqli_fetch_assoc($current_stok_result);
            $current_stok = $current_stok_row['stok'];

            if ($current_stok > 0) {
                // Kurangi stok di tabel data_buku
                $sql_update_stok = "UPDATE data_buku SET stok = stok - 1 WHERE kode_buku = '$kode_buku'";
                if (mysqli_query($koneksi, $sql_update_stok)) {
                    // Masukkan data peminjaman ke tabel peminjaman
                    $sql_insert = "INSERT INTO peminjaman (kode_buku, nama_peminjam, tanggal_pinjam, tanggal_kembali_target, status)
                                 VALUES ('$kode_buku', '$nama_peminjam', '$tanggal_pinjam', '$tanggal_kembali_target', 'dipinjam')";

                    if (mysqli_query($koneksi, $sql_insert)) {
                        $message = "Buku berhasil dipinjam oleh " . htmlspecialchars($nama_peminjam) . ". Stok buku berkurang.";
                        // Redirect ke index.php setelah sukses
                        header("Location: index.php?status_peminjaman=" . urlencode($message));
                        exit();
                    } else {
                        // Jika insert peminjaman gagal, kembalikan stoknya
                        mysqli_query($koneksi, "UPDATE data_buku SET stok = stok + 1 WHERE kode_buku = '$kode_buku'");
                        $message = "Error saat menyimpan data peminjaman: " . mysqli_error($koneksi);
                    }
                } else {
                    $message = "Error saat mengurangi stok buku: " . mysqli_error($koneksi);
                }
            } else {
                $message = "Maaf, stok buku ini sudah habis.";
            }
        }
    }
} else {
    $message = "Kode buku tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .book-details {
            margin-top: 15px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            text-align: left;
        }
        .book-details p {
            margin: 5px 0;
            color: #555;
        }
        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pinjam Buku</h1>
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'Error') !== false || strpos($message, 'Maaf') !== false ? 'error-message' : '' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($book_data): // Pastikan $book_data tidak null ?>
            <div class="book-details">
                <p><strong>Kode Buku:</strong> <?= htmlspecialchars($book_data['kode_buku']) ?></p>
                <p><strong>Judul Buku:</strong> <?= htmlspecialchars($book_data['judul_buku']) ?></p>
                <p><strong>Penulis:</strong> <?= htmlspecialchars($book_data['penulis']) ?></p>
                <p><strong>Stok Tersedia:</strong> <?= htmlspecialchars($book_data['stok']) ?></p>
            </div>

            <?php if ($book_data['stok'] > 0): // Tampilkan form hanya jika stok > 0 ?>
                <form action="" method="POST">
                    <label for="nama_peminjam">Nama Peminjam:</label>
                    <input type="text" id="nama_peminjam" name="nama_peminjam" required>
                    <input type="submit" name="submit_pinjam" value="Konfirmasi Pinjam">
                </form>
            <?php else: ?>
                <p style="color: red; font-weight: bold;">Stok buku ini sudah habis.</p>
            <?php endif; ?>

        <?php else: ?>
            <p>Silakan pilih buku dari daftar untuk dipinjam.</p>
        <?php endif; ?>
        <a href="index.php" class="back-link">Kembali ke index</a>
    </div>
</body>
</html>