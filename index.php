<?php
    session_start();
    include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
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

    h1 {
        color: #2c3e50;
        margin-bottom: 25px; /* More space below heading */
        font-weight: 700; /* Bolder font for heading */
        border-bottom: 3px solid #007bff; /* Changed to a modern blue accent, consistent with login */
        padding-bottom: 12px; /* Slightly more padding */
        text-align: center;
        font-size: 2.2rem; /* Larger heading font */
        letter-spacing: 0.5px; /* Slight letter spacing */
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: rgba(255, 255, 255, 0.95); /* Slightly less transparent, matches login box */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow, matches login box */
        border-radius: 15px; /* More rounded corners, matches login box */
        overflow: hidden; /* Ensures rounded corners apply to content */
        border: 1px solid #e0e0e0; /* Subtle border */
    }

    th, td {
        padding: 15px 20px; /* Increased padding for table cells */
        text-align: left;
        border-bottom: 1px solid #eee; /* Lighter border for rows */
        font-size: 1.05rem; /* Slightly larger text */
    }

    th {
        background-color: #e9ecef; /* Lighter header background */
        font-weight: 600; /* Bolder header text */
        color: #495057; /* Slightly darker grey for header text */
        text-transform: uppercase; /* Uppercase header text */
        letter-spacing: 0.2px;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa; /* Even softer alternating row color */
    }

    tr:hover {
        background-color: #e2e6ea; /* Lighter hover color */
        cursor: pointer; /* Indicates interactivity */
    }

    td {
        text-align: center; /* Center content in data cells by default */
    }

    td:first-child, th:first-child {
        text-align: left; /* Keep specific columns left-aligned */
    }

    td:nth-child(3), th:nth-child(3) {
        text-align: left; /* Keep specific columns left-aligned */
    }

    td img {
        max-width: 80px; /* Smaller size for table view, matching admin.php */
        height: auto;
        display: block;
        margin: 0 auto;
        border-radius: 8px; /* More rounded image corners */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Stronger image shadow */
        transition: transform 0.2s ease-in-out;
    }

    td img:hover {
        transform: scale(1.05); /* Slight zoom on image hover */
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px; /* More space between buttons */
    }

    .action-buttons a {
        padding: 10px 15px; /* Larger button padding */
        border-radius: 8px; /* More rounded buttons */
        text-decoration: none;
        font-weight: 600; /* Bolder button text */
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle button shadow */
    }

    .action-buttons a.borrow-btn {
        background-color: #007bff; /* Changed to primary blue for consistency */
    }

    .action-buttons a.borrow-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .action-buttons a.return-btn {
        background-color: #dc3545; /* Red for return */
    }

    .action-buttons a.return-btn:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>
<body>
<h1>DATA BUKU<br>SMK Telkom Lampung<br>Tahun 2025-2026</h1>

    <table border="1"> <thead>
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
                <th>Aksi</th>
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
                    <?php if (!empty($data['gambar_buku'])) { ?>
                        <img src="<?= htmlspecialchars($data['gambar_buku']) ?>" alt="Gambar Buku">
                    <?php } else {
                        echo "Tidak ada gambar";
                    } ?>
                </td>
                <td class="action-buttons">
                    <a href="pinjam.php?kode_buku=<?= urlencode($data['kode_buku']) ?>" class="borrow-btn">Pinjam</a>
                    <a href="kembalikan.php?kode_buku=<?= urlencode($data['kode_buku']) ?>" class="return-btn">Kembalikan</a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</body>
</html>