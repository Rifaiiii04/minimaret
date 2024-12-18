<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: daftar_produk.php");
    exit;
}

$id = $_GET['id'];

if (isset($_POST['update'])) {
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $harga = $conn->real_escape_string($_POST['harga']);
    $stok = $conn->real_escape_string($_POST['stok']);

    $query = "UPDATE produk SET nama_produk='$nama_produk', harga='$harga', stok='$stok' WHERE id='$id'";
    if ($conn->query($query)) {
        header("Location: daftar_produk.php");
        exit;
    } else {
        $error = "Gagal memperbarui produk: " . $conn->error;
    }
}

$query = "SELECT * FROM produk WHERE id='$id'";
$result = $conn->query($query);
$produk = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
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

        .form-container {
            width: 90%;
            max-width: 600px;
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

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form input {
            margin: 10px 0;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        form button {
            background-color: #180161;
            color: #fff;
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #540096;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            h1 {
                font-size: 1.8rem;
            }

            form input, form button {
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
    <div class="form-container">
        <h1>Edit Produk</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?php echo $produk['nama_produk']; ?>" required>
            <label for="harga">Harga</label>
            <input type="number" id="harga" name="harga" value="<?php echo $produk['harga']; ?>" required>
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" value="<?php echo $produk['stok']; ?>" required>
            <button type="submit" name="update">Update</button>
        </form>
        <br>
        <a href="daftar_produk.php" class="btn" style="background-color: #4CAF50;">Kembali</a>
    </div>
</body>
</html>
