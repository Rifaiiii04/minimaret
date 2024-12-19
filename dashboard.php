<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Mengambil total produk
$query_produk = "SELECT COUNT(*) AS total_produk FROM produk";
$result_produk = $conn->query($query_produk);
$total_produk = $result_produk->fetch_assoc()['total_produk'];

// Mengambil total transaksi
$query_transaksi = "SELECT COUNT(*) AS total_transaksi FROM transaksi";
$result_transaksi = $conn->query($query_transaksi);
$total_transaksi = $result_transaksi->fetch_assoc()['total_transaksi'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        }

        .dashboard-container {
            width: 100%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            color: #180161;
            text-align: center;
            margin-bottom: 30px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .info-box h2 {
            font-size: 1.5rem;
            color: #180161;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 1.2rem;
            color: #555;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
        }

        nav ul li {
            font-size: 1.1rem;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            background-color: #180161;
            padding: 12px 20px;
            border-radius: 4px;
            transition: all 0.3s ease-in-out;
        }

        nav ul li a:hover {
            background-color: #540096;
            transform: translateY(-2px);
        }

        nav ul li a:active {
            background-color: #12064d;
        }

        /* Add responsive design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            .info-section {
                flex-direction: column;
                align-items: center;
            }

            .info-box {
                width: 100%;
                margin-bottom: 20px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>POS Minimart</h1>
        <div class="info-section">
            <div class="info-box">
                <h2>Total Produk</h2>
                <p><?php echo $total_produk; ?> Produk</p>
            </div>
            <div class="info-box">
                <h2>Total Transaksi</h2>
                <p><?php echo $total_transaksi; ?> Transaksi</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="daftar_produk.php">Manage Produk</a></li>
                <li><a href="transaksi.php">Transaksi</a></li>
                <li><a href="manage_user.php">Manage User</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
