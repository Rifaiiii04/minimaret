<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Logic untuk transaksi
if (isset($_POST['submit'])) {
    $total_harga = 0;
    $items = $_POST['items'];
    $tanggal = date('Y-m-d');

    $query_transaksi = "INSERT INTO transaksi (tanggal, total_harga) VALUES ('$tanggal', 0)";
    $conn->query($query_transaksi);
    $transaksi_id = $conn->insert_id;

    foreach ($items as $item) {
        $produk_id = $item['produk_id'];
        $jumlah = $item['jumlah'];

        $query_produk = "SELECT harga, stok FROM produk WHERE id = '$produk_id'";
        $result = $conn->query($query_produk);
        $produk = $result->fetch_assoc();

        if ($produk['stok'] >= $jumlah) {
            $subtotal = $produk['harga'] * $jumlah;
            $total_harga += $subtotal;

            $query_detail = "INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, subtotal) 
                             VALUES ('$transaksi_id', '$produk_id', '$jumlah', '$subtotal')";
            $conn->query($query_detail);
            $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE id = '$produk_id'");
        }
    }

    $conn->query("UPDATE transaksi SET total_harga = '$total_harga' WHERE id = '$transaksi_id'");
    $success = "Transaksi berhasil disimpan.";
}

// Menampilkan riwayat transaksi
$query_riwayat = "SELECT t.id, t.tanggal, t.total_harga, 
                  GROUP_CONCAT(p.nama_produk, ' (', dt.jumlah, ')') AS detail_produk 
                  FROM transaksi t
                  JOIN detail_transaksi dt ON t.id = dt.transaksi_id
                  JOIN produk p ON dt.produk_id = p.id
                  GROUP BY t.id";
$riwayat = $conn->query($query_riwayat);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <style>
       /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    display: flex;
    justify-content: center;
    padding: 20px;
}

.container {
    max-width: 900px;
    width: 100%;
    background-color: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Header Styles */
h1, h2 {
    color: #180161;
    text-align: center;
    margin-bottom: 20px;
}

/* Form Styles */
form {
    margin-bottom: 30px;
}

.item {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

select, input[type="number"], button {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f5f5f5;
}

select:focus, input[type="number"]:focus {
    outline: none;
    border-color: #180161;
    background-color: #fff;
}

button {
    background-color: #180161;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #540096;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

table th {
    background-color: #180161;
    color: #fff;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #f5f5f5;
}

/* Success Message */
.success {
    color: #155724;
    background-color: #d4edda;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

/* Link Styles */
a {
    display: block;
    width: 100%;
    padding: 12px 0;
    text-align: center;
    background-color: #4CAF50;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
    font-size: 1rem;
}

a:hover {
    background-color: #45a049;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    table th, table td {
        font-size: 0.9rem;
    }

    button, select, input {
        font-size: 0.9rem;
        padding: 8px;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h1>Transaksi Penjualan</h1>

    <!-- Pesan sukses -->
    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

    <!-- Form Input Barang -->
    <form action="transaksi.php" method="POST">
        <div id="items-container">
            <div class="item">
                <select name="items[0][produk_id]" required>
                    <option value="">Pilih Produk</option>
                    <?php
                    $query = "SELECT * FROM produk";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nama_produk']} (Stok: {$row['stok']})</option>";
                    }
                    ?>
                </select>
                <input type="number" name="items[0][jumlah]" placeholder="Jumlah" min="1" required>
            </div>
        </div>
        <button type="submit" name="submit">Simpan Transaksi</button>
    </form>

    <!-- Riwayat Transaksi -->
    <h2>Riwayat Transaksi</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Detail Produk</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $riwayat->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= $row['detail_produk'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Tombol kembali ke dashboard -->
    <a href="dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html>
