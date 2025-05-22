<?php
session_start();
include("koneksi.php");

// Inisialisasi variabel untuk pesan, agar tidak ada undefined variable notice
$message = "";
$borrowed_books = []; // Inisialisasi sebagai array kosong

if (isset($_GET['kode_buku'])) {
    $kode_buku = mysqli_real_escape_string($koneksi, $_GET['kode_buku']);

    // Ambil data peminjaman aktif untuk buku ini
    // Pastikan hanya mengambil yang statusnya 'dipinjam'
    $sql_borrowed = "SELECT * FROM peminjaman WHERE kode_buku = '$kode_buku' AND status = 'dipinjam'";
    $result_borrowed = mysqli_query($koneksi, $sql_borrowed);

    // Cek apakah query berhasil dan ada hasil
    if ($result_borrowed) {
        $borrowed_books = mysqli_fetch_all($result_borrowed, MYSQLI_ASSOC);
        if (empty($borrowed_books)) {
            $message = "Tidak ada riwayat peminjaman aktif untuk buku ini.";
        }
    } else {
        $message = "Error saat mengambil data peminjaman: " . mysqli_error($koneksi);
    }

    // Logika ketika form pengembalian disubmit
    if (isset($_POST['submit_kembali'])) {
        $id_peminjaman = mysqli_real_escape_string($koneksi, $_POST['id_peminjaman']);
        $tanggal_kembali_aktual = date('Y-m-d');

        // Pertama, update status peminjaman di tabel peminjaman
        $sql_update_peminjaman = "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali_aktual = '$tanggal_kembali_aktual' WHERE id_peminjaman = '$id_peminjaman' AND status = 'dipinjam'";

        if (mysqli_query($koneksi, $sql_update_peminjaman)) {
            // Jika update status peminjaman berhasil, baru tambahkan stok di data_buku
            $sql_add_stok = "UPDATE data_buku SET stok = stok + 1 WHERE kode_buku = '$kode_buku'";

            if (mysqli_query($koneksi, $sql_add_stok)) {
                // Set pesan sukses ke session untuk ditampilkan di index.php
                $_SESSION['message'] = "Buku berhasil dikembalikan. Stok buku bertambah.";
                // Redirect ke index.php
                header("Location: index.php");
                exit();
            } else {
                // Jika gagal menambah stok (misalnya, masalah database), berikan pesan error
                $_SESSION['message'] = "Error saat menambahkan stok buku: " . mysqli_error($koneksi);
                // Redirect ke index.php dengan pesan error
                header("Location: index.php");
                exit();
            }
        } else {
            // Jika update status peminjaman gagal, berikan pesan error
            $_SESSION['message'] = "Error saat mengupdate status peminjaman: " . mysqli_error($koneksi);
            // Redirect ke index.php dengan pesan error
            header("Location: index.php");
            exit();
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
    <title>Kembalikan Buku</title>
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
            width: 600px; /* Wider for borrowed list */
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
        .borrowed-list {
            margin-top: 20px;
            text-align: left;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
        }
        .borrowed-list h3 {
            margin-top: 0;
            color: #333;
        }
        .borrowed-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
        }
        .borrowed-item:last-child {
            border-bottom: none;
        }
        .borrowed-item span {
            flex-grow: 1;
            color: #555;
        }
        .borrowed-item form {
            margin: 0;
            display: inline-block;
        }
        .borrowed-item button {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .borrowed-item button:hover {
            background-color: #218838;
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
        <h1>Kembalikan Buku</h1>
        <?php
        // Menampilkan pesan dari session
        if (isset($_SESSION['message'])) {
            $msg_class = (strpos($_SESSION['message'], 'Error') !== false || strpos($_SESSION['message'], 'Tidak ada') !== false) ? 'error-message' : '';
            echo "<div class='message " . $msg_class . "'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
        } elseif ($message) { // Menampilkan pesan yang diset di halaman ini (jika tidak ada dari session)
            $msg_class = (strpos($message, 'Error') !== false || strpos($message, 'Tidak ada') !== false) ? 'error-message' : '';
            echo "<div class='message " . $msg_class . "'>" . $message . "</div>";
        }
        ?>

        <?php if (!empty($borrowed_books)): ?>
            <div class="borrowed-list">
                <h3>Riwayat Peminjaman Aktif untuk Buku Ini:</h3>
                <?php foreach ($borrowed_books as $borrow): ?>
                    <div class="borrowed-item">
                        <span><strong>Peminjam:</strong> <?= htmlspecialchars($borrow['nama_peminjam']) ?> | <strong>Tanggal Pinjam:</strong> <?= htmlspecialchars($borrow['tanggal_pinjam']) ?></span>
                        <form action="" method="POST">
                            <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($borrow['id_peminjaman']) ?>">
                            <button type="submit" name="submit_kembali">Kembalikan Ini</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada buku ini yang sedang dipinjam saat ini.</p>
        <?php endif; ?>
        <a href="index.php" class="back-link">Kembali ke index</a>
    </div>
</body>
</html>