<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Proses tambah user baru
if (isset($_POST['submit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($query)) {
        $success = "User berhasil ditambahkan.";
    } else {
        $error = "Gagal menambahkan user: " . $conn->error;
    }
}

// Proses hapus user
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM users WHERE id='$delete_id'";
    if ($conn->query($query)) {
        header("Location: manage_user.php");
        exit;
    } else {
        $error = "Gagal menghapus user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
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
            flex-direction: column;
            height: 100vh;
            margin: 0;
            padding-top: 50px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            color: #180161;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container, .table-container {
            margin-bottom: 40px;
        }

        .form-container h2, .table-container h2 {
            color: #180161;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .error, .success {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #180161;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #540096;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
            color: #333;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        table a {
            color: #d9534f;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }

        /* Tombol Kembali */
        .btn-back {
            display: block;
            width: 200px;
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px auto;
            font-size: 1rem;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .form-container, .table-container {
                margin-bottom: 20px;
            }

            form input, form button {
                padding: 10px;
            }

            table th, table td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola User</h1>
        
        <!-- Bagian Tambah User -->
        <div class="form-container">
            <h2>Tambah User</h2>
            <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit">Tambah</button>
            </form>
        </div>
        
        <!-- Bagian Daftar User -->
        <div class="table-container">
            <h2>Daftar User</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM users";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>
                                <a href='manage_user.php?delete_id={$row['id']}' onclick='return confirm(\"Hapus user ini?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol Kembali -->
        <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    </div>
</body>
</html>
