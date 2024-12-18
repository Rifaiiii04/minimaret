<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Pencarian produk
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <style>
        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            line-height: 1.6;
        }

        .table-container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            color: #180161;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #180161;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #540096;
        }

        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-form input {
            width: 80%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #180161;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #540096;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #180161;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table td a {
            color: #180161;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        table td a:hover {
            color: #540096;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-container {
                padding: 20px;
            }

            table {
                font-size: 0.9rem;
            }

            table th, table td {
                padding: 10px;
            }

            .btn {
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h1>Daftar Produk</h1>
        <a href="tambah_produk.php" class="btn">Tambah Produk</a>
        <a href="dashboard.php" class="btn" style="background-color: #4CAF50;">Kembali ke Dashboard</a> 

        <!-- Form Pencarian -->
        <form class="search-form" action="" method="GET">
            <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search_query%'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['nama_produk']}</td>";
                        echo "<td>{$row['harga']}</td>";
                        echo "<td>{$row['stok']}</td>";
                        echo "<td>
                                <a href='edit_produl.php?id={$row['id']}'>Edit</a> | 
                                <a href='hapus_produk.php?id={$row['id']}' onclick='return confirm(\"Hapus produk ini?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada produk yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
