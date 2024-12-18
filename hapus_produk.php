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

$query = "DELETE FROM produk WHERE id='$id'";
if ($conn->query($query)) {
    header("Location: daftar_produk.php");
    exit;
} else {
    echo "Gagal menghapus produk: " . $conn->error;
}
?>
