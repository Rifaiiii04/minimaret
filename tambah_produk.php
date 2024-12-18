<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $harga = $conn->real_escape_string($_POST['harga']);
    $stok = $conn->real_escape_string($_POST['stok']);
    $query = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', '$harga', '$stok')";
    if ($conn->query($query)) {
        $success = "Produk berhasil ditambahkan.";
    } else {
        $error = "Gagal menambahkan produk: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            color: #180161;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #180161;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3c137b;
        }

        .success, .error {
            padding: 10px;
            background-color: #f4ffdd;
            color: #728a2d;
            margin-bottom: 20px;
            border: 1px solid #a5d392;
            border-radius: 4px;
        }

        .error {
            background-color: #ffdddd;
            color: #e53946;
            border: 1px solid #f44336;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                width: 90%;
                max-width: 100%;
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
    <div class="form-container">
        <h1>Tambah Produk</h1> 
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <input type="text" name="nama_produk" placeholder="Nama Produk" required>
            <input type="number" name="harga" placeholder="Harga" required>
            <input type="number" name="stok" placeholder="Stok" required>
            <button type="submit" name="submit">Tambah</button>
        </form><br>
        <a href="daftar_produk.php" class="btn" style="background-color: #4CAF50;">Kembali</a>
    </div>
</body>
</html>
