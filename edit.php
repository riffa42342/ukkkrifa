<?php
session_start();
include("koneksi.php");

$data = []; // Inisialisasi $data sebagai array kosong untuk menghindari undefined variable

if(isset($_GET['no_buku'])){
    $id_to_edit = mysqli_real_escape_string($koneksi, $_GET['no_buku']); // Bersihkan input
    $sql_select = "SELECT * FROM data_buku WHERE no_buku=$id_to_edit";
    $result_select = mysqli_query($koneksi,$sql_select);

    if($result_select && mysqli_num_rows($result_select) > 0){
        $data = mysqli_fetch_assoc($result_select);
    } else {
        echo "<script>alert('Buku tidak ditemukan!'); window.location.href='admin.php';</script>";
        exit;
    }
}

if(isset($_POST['edit'])){
    $id = mysqli_real_escape_string($koneksi, $_POST['no_buku_lama']);
    $kode_buku = mysqli_real_escape_string($koneksi, $_POST['kode_buku']);
    $no_buku = mysqli_real_escape_string($koneksi, $_POST['no_buku']);
    $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $jumlah_halaman = mysqli_real_escape_string($koneksi, $_POST['jumlah_halaman']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']); // Menangkap nilai stok dari form

    $gambar_buku_path = $_POST['gambar_buku_lama'] ?? '';

    // Proses upload gambar baru
    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["gambar_buku"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["gambar_buku"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File bukan gambar.');</script>";
            $uploadOk = 0;
        }

        if ($_FILES["gambar_buku"]["size"] > 5000000) {
            echo "<script>alert('Ukuran file terlalu besar (Max 5MB).');</script>";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "<script>alert('Hanya format JPG, JPEG, PNG & GIF yang diizinkan.');</script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<script>alert('Maaf, file Anda tidak terunggah.');</script>";
        } else {
            if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                // Hapus gambar lama jika ada dan berhasil diunggah
                if (!empty($gambar_buku_path) && file_exists($gambar_buku_path)) {
                    unlink($gambar_buku_path);
                }
                $gambar_buku_path = $target_file;
            } else {
                echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file Anda.');</script>";
            }
        }
    }

    // Pastikan semua variabel numerik di-cast ke tipe data yang sesuai
    $no_buku = (int)$no_buku;
    $tahun_terbit = (int)$tahun_terbit;
    $jumlah_halaman = (int)$jumlah_halaman;
    $harga = (float)$harga;
    $stok = (int)$stok; // Pastikan stok adalah integer

    // Query UPDATE dengan kolom 'stok'
    // HATI-HATI: Pastikan tidak ada komentar PHP di dalam string SQL
    $sql_update = "UPDATE data_buku SET
        kode_buku='$kode_buku',
        no_buku=$no_buku,
        judul_buku='$judul_buku',
        tahun_terbit=$tahun_terbit,
        penulis='$penulis',
        penerbit='$penerbit',
        jumlah_halaman=$jumlah_halaman,
        harga=$harga,
        gambar_buku='$gambar_buku_path',
        stok=$stok
        WHERE no_buku=$id";

    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>alert('Buku berhasil diupdate!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . mysqli_error($koneksi) . "');</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 20px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        form {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 450px;
            max-width: 90%;
            display: flex;
            flex-direction: column;
            gap: 18px;
            border: 1px solid #e0e0e0;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 700;
            border-bottom: 3px solid #007bff;
            padding-bottom: 12px;
            text-align: center;
            font-size: 2em;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: calc(100% - 20px);
            padding: 14px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1.05rem;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background-color: #fdfdfd;
        }

        input[type="file"] {
            padding: 10px;
            line-height: 1.5;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: 600;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            letter-spacing: 0.5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        input[type="submit"]:active {
            background-color: #004085;
            transform: translateY(0);
            box-shadow: none;
        }

        .current-image-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .current-image-container img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .current-image-container p {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <form action="edit.php" method="post" enctype="multipart/form-data">
        <h2>Edit Data Buku</h2>
        <input type="hidden" name="no_buku_lama" value="<?= htmlspecialchars($data['no_buku'] ?? '') ?>">
        <input type="hidden" name="gambar_buku_lama" value="<?= htmlspecialchars($data['gambar_buku'] ?? '') ?>">

        <input type="text" name="kode_buku" placeholder="Kode Buku" value="<?= htmlspecialchars($data['kode_buku'] ?? '') ?>" required>
        <input type="number" name="no_buku" placeholder="Nomor Buku" value="<?= htmlspecialchars($data['no_buku'] ?? '') ?>" required>
        <input type="text" name="judul_buku" placeholder="Judul Buku" value="<?= htmlspecialchars($data['judul_buku'] ?? '') ?>" required>
        <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" value="<?= htmlspecialchars($data['tahun_terbit'] ?? '') ?>" required>
        <input type="text" name="penulis" placeholder="Penulis" value="<?= htmlspecialchars($data['penulis'] ?? '') ?>" required>
        <input type="text" name="penerbit" placeholder="Penerbit" value="<?= htmlspecialchars($data['penerbit'] ?? '') ?>" required>
        <input type="number" name="jumlah_halaman" placeholder="Jumlah Halaman" value="<?= htmlspecialchars($data['jumlah_halaman'] ?? '') ?>" required>
        <input type="number" name="harga" placeholder="Harga" value="<?= htmlspecialchars($data['harga'] ?? '') ?>" required>
        <input type="number" name="stok" placeholder="Stok Buku" value="<?= htmlspecialchars($data['stok'] ?? '') ?>" required>

        <label for="gambar_buku" style="text-align: left; font-weight: bold; color: #555;">Gambar Buku:</label>
        <?php if (!empty($data['gambar_buku'])) { ?>
            <div class="current-image-container">
                <p>Gambar Saat Ini:</p>
                <img src="<?= htmlspecialchars($data['gambar_buku']) ?>" alt="Gambar Buku Saat Ini">
                <p>Pilih file baru untuk mengganti gambar.</p>
            </div>
        <?php } else { ?>
            <p style="text-align: center; color: #777;">Belum ada gambar buku.</p>
        <?php } ?>
        <input type="file" name="gambar_buku" id="gambar_buku" accept="image/*">
        <input type="submit" name="edit" value="UPDATE BUKU">
    </form>
</body>
</html>