<?php
include("koneksi.php");

if (isset($_GET['no_buku'])) {
    $no_buku = $_GET['no_buku'];

    if (isset($_POST['konfirmasi'])) {
        if ($_POST['konfirmasi'] === 'ya') {
            mysqli_query($koneksi, "DELETE FROM data_buku WHERE no_buku=$no_buku");
            header("Location: admin.php");
            exit();
        } else {
            header("Location: admin.php"); 
            exit();
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Konfirmasi Hapus Buku</title>
        <link rel="stylesheet" href="style.css"> <style>
            .konfirmasi-box {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            }
            .konfirmasi-button {
                padding: 10px 15px;
                margin: 10px;
                border-radius: 5px;
                cursor: pointer;
            }
            .ya-button {
                background-color: #dc3545;
                color: white;
                border: none;
            }
            .tidak-button {
                background-color: #0056b3;
                color: white;
                border: none;
            }
        </style>
    </head>
    <body>
        <div class="konfirmasi-box">
            <h2>Konfirmasi Penghapusan</h2>
            <p>Apakah Anda yakin ingin menghapus buku dengan No. Buku: <?php echo $no_buku; ?>?</p>
            <form method="post">
                <input type="hidden" name="no_buku" value="<?php echo $no_buku; ?>">
                <button type="submit" name="konfirmasi" value="ya" class="konfirmasi-button ya-button">Ya, Hapus</button>
                <button type="submit" name="konfirmasi" value="tidak" class="konfirmasi-button tidak-button">Tidak, Batal</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    header("Location: admin.php"); 
}
?>