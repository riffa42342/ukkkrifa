<?php
session_start();
include('koneksi.php');

if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'];
    $no_buku = $_POST['no_buku'];
    $judul_buku = $_POST['judul_buku'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok']; // <<< Menangkap nilai stok dari form

    $gambar_buku = '';
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
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["gambar_buku"]["size"] > 50000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                $gambar_buku = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $kode_buku = mysqli_real_escape_string($koneksi, $kode_buku);
    $judul_buku = mysqli_real_escape_string($koneksi, $judul_buku);
    $penulis = mysqli_real_escape_string($koneksi, $penulis);
    $penerbit = mysqli_real_escape_string($koneksi, $penerbit);
    // Pastikan semua variabel numerik juga di-cast atau divalidasi jika perlu
    $no_buku = (int)$no_buku;
    $tahun_terbit = (int)$tahun_terbit;
    $jumlah_halaman = (int)$jumlah_halaman;
    $harga = (float)$harga; // Harga bisa float/decimal
    $stok = (int)$stok; // <<< Pastikan stok adalah integer

    // <<< Menambahkan kolom 'stok' ke dalam query INSERT
    $sql = "INSERT INTO data_buku(kode_buku, no_buku, judul_buku, tahun_terbit, penulis, penerbit, jumlah_halaman, harga, gambar_buku, stok)
             VALUES ('$kode_buku', $no_buku, '$judul_buku', $tahun_terbit, '$penulis', '$penerbit', $jumlah_halaman, $harga, '$gambar_buku', $stok)";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Buku berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PAGE</title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5; /* Lighter, more modern background */
    margin: 20px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #333; /* Default text color */
}

/* Styling for the Add Book Form */
form {
    background-color: rgba(255, 255, 255, 0.95); /* Slightly less transparent, matches login box */
    border-radius: 15px; /* More rounded corners, consistent with login and data_buku */
    padding: 40px; /* Increased padding */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
    width: 450px; /* Slightly wider form */
    max-width: 90%; /* Responsive width */
    display: flex;
    flex-direction: column;
    gap: 18px; /* Increased space between inputs */
    margin-bottom: 40px; /* More margin below the form before the table */
    border: 1px solid #e0e0e0; /* Subtle border */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

form:hover {
    transform: translateY(-5px); /* Subtle lift on hover */
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

h2 {
    color: #2c3e50;
    margin-bottom: 25px; /* More space below heading */
    font-weight: 700; /* Bolder font for heading */
    border-bottom: 3px solid #007bff; /* Consistent modern blue accent */
    padding-bottom: 12px; /* Slightly more padding */
    text-align: center;
    font-size: 2em; /* Larger heading font */
    letter-spacing: 0.5px;
}

input[type="text"],
input[type="number"],
input[type="file"] { /* Added file input styling */
    width: calc(100% - 20px); /* Adjust width for padding */
    padding: 14px 10px; /* More vertical padding */
    border: 1px solid #ccc; /* Lighter border color */
    border-radius: 8px; /* Slightly more rounded inputs */
    box-sizing: border-box;
    font-size: 1.05rem; /* Slightly larger text in inputs */
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    background-color: #fdfdfd; /* Subtle off-white background for inputs */
}

input[type="file"] { /* Specific styling for file input */
    padding: 10px; /* Adjust padding for file input */
    line-height: 1.5; /* Improve vertical alignment */
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="file"]:focus { /* Added file input focus styling */
    border-color: #007bff; /* Focus color matches accent */
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Glow effect on focus */
}

input[type="submit"] {
    width: 100%;
    padding: 15px 20px; /* Larger button padding */
    background-color: #007bff; /* Changed to primary blue */
    color: white;
    border: none;
    border-radius: 8px; /* Matches input border-radius */
    cursor: pointer;
    font-size: 1.2rem; /* Larger font for button */
    font-weight: 600; /* Bolder button text */
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    letter-spacing: 0.5px;
}

input[type="submit"]:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: translateY(-3px); /* Subtle lift on hover */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Shadow on hover */
}

input[type="submit"]:active {
    background-color: #004085; /* Even darker blue on active */
    transform: translateY(0); /* Resets on click */
    box-shadow: none; /* Removes shadow on active */
}

/* Styling for the Book Data Table (similar to data_buku.php) */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: rgba(255, 255, 255, 0.95); /* Consistent with form background */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* Consistent shadow */
    border-radius: 15px; /* Consistent rounded corners */
    overflow: hidden;
    border: 1px solid #e0e0e0;
}

th, td {
    padding: 15px 20px; /* Increased padding */
    text-align: left;
    border-bottom: 1px solid #eee; /* Lighter border for rows */
    font-size: 1.05rem;
}

th {
    background-color: #e9ecef; /* Lighter header background */
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

tr:nth-child(even) {
    background-color: #f8f9fa; /* Softer alternating row color */
}

tr:hover {
    background-color: #e2e6ea; /* Lighter hover color */
    cursor: pointer;
}

td {
    text-align: center; /* Center content by default */
}

td:first-child, th:first-child {
    text-align: left; /* Keep specific columns left-aligned */
}

td:nth-child(3), th:nth-child(3) {
    text-align: left; /* Keep specific columns left-aligned */
}

td img {
    max-width: 80px; /* Adjust size for admin view */
    height: auto;
    display: block;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

td img:hover {
    transform: scale(1.05);
}

/* Styles for Edit and Delete buttons */
td a {
    text-decoration: none;
    padding: 10px 15px; /* Larger button padding */
    border-radius: 8px; /* More rounded buttons */
    margin: 0 5px; /* Space between buttons */
    color: white;
    font-weight: 600;
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle button shadow */
    display: inline-block; /* Ensure margin applies correctly */
}

td a:first-of-type { /* Use first-of-type for Edit button */
    background-color: #007bff; /* Blue for Edit, consistent with primary action */
}

td a:first-of-type:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

td a:last-of-type { /* Use last-of-type for Delete button */
    background-color: #dc3545; /* Red for Delete */
}

td a:last-of-type:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}
    </style>
</head>
<body>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <h2>Tambah Buku Baru</h2>
        <input type="text" name="kode_buku" placeholder="Kode Buku" required>
        <input type="number" name="no_buku" placeholder="Nomor Buku" required>
        <input type="text" name="judul_buku" placeholder="Judul Buku" required>
        <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" required>
        <input type="text" name="penulis" placeholder="Penulis" required>
        <input type="text" name="penerbit" placeholder="Penerbit" required>
        <input type="number" name="jumlah_halaman" placeholder="Jumlah Halaman" required>
        <input type="number" name="harga" placeholder="Harga" required>
        <input type="number" name="stok" placeholder="Stok Buku" required> <label for="gambar_buku" style="text-align: left; font-weight: bold; color: #555;">Pilih Gambar Buku:</label>
        <input type="file" name="gambar_buku" id="gambar_buku" accept="image/*" required>
        <input type="submit" name="tambah" value="TAMBAH BUKU">
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>kode buku</th>
                <th>no buku</th>
                <th>judul buku</th>
                <th>tahun terbit</th>
                <th>penulis</th>
                <th>penerbit</th>
                <th>jumlah halaman</th>
                <th>harga</th>
                <th>gambar buku</th>
                <th>Stok</th> <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sql="SELECT * FROM data_buku";
            $row=mysqli_query($koneksi,$sql);
            while ($data = mysqli_fetch_array($row)){
        ?>
            <tr>
                <td><?= htmlspecialchars($data ['kode_buku']) ?></td>
                <td><?= htmlspecialchars($data ['no_buku']) ?></td>
                <td><?= htmlspecialchars($data ['judul_buku']) ?></td>
                <td><?= htmlspecialchars($data ['tahun_terbit']) ?></td>
                <td><?= htmlspecialchars($data ['penulis']) ?></td>
                <td><?= htmlspecialchars($data ['penerbit']) ?></td>
                <td><?= htmlspecialchars($data ['jumlah_halaman']) ?></td>
                <td><?= htmlspecialchars($data ['harga']) ?></td>
                <td>
                    <?php if ($data['gambar_buku']) { ?>
                        <img src="<?= htmlspecialchars($data['gambar_buku']) ?>" alt="Gambar Buku">
                    <?php } else {
                        echo "Tidak ada gambar";
                    } ?>
                </td>
                <td><?= htmlspecialchars($data ['stok']) ?></td> <td>
                    <a href="edit.php?no_buku=<?= htmlspecialchars($data['no_buku']) ?>">Edit</a>
                    <a href="hapus.php?no_buku=<?= htmlspecialchars($data['no_buku']) ?>">Hapus</a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</body>
</html>