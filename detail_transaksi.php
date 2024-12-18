<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: riwayat_transaksi.php");
    exit;
}

$transaksi_id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
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
            max-width: 1000px;
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

        /* Style untuk link */
        table td a {
            color: #180161;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
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
    </style>
</head>
<body>
    <div class="table-container">
    <a href="dashboard.php" class="btn" style="background-color: #4CAF50;">Kembali ke Dashboard</a> 
        <h1>Detail Transaksi #<?php echo $transaksi_id; ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT p.nama_produk, d.jumlah, d.subtotal 
                          FROM detail_transaksi d
                          JOIN produk p ON d.produk_id = p.id
                          WHERE d.transaksi_id = '$transaksi_id'";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['nama_produk']}</td>";
                    echo "<td>{$row['jumlah']}</td>";
                    echo "<td>{$row['subtotal']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
