<?php
include "koneksi.php";

$id_expenses = $_GET['id_expenses'];

$query = "SELECT * FROM pengeluaran WHERE id_expenses = $id_expenses";
$result = $koneksi->query($query);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Pengeluaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .input {
            width: 300px;
        }
    </style>
</head>
<body style="background-color: #D5D3CC; color: #19350C;">
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh">
        <p><b>Apa, ya, yang salah?</b></p>
        <form action="update_process.php" method="post">
            <input type="hidden" name="id_expenses" class="input" value="<?= $row['id_expenses'] ?>">
            Tanggal<br><input type="date" name="tanggal" class="input" value="<?= $row['tanggal'] ?>"><br>
            Pengeluaran<br><input type="text" name="pengeluaran" class="input" value="<?= $row['pengeluaran'] ?>"><br>
            Deskripsi<br><input type="text" name="deskripsi" class="input" value="<?= $row['deskripsi'] ?>"><br>
            Jenis<br><input type="text" name="jenis" class="input" value="<?= $row['jenis'] ?>"><br>
            Besaran<br><input type="number" name="besaran" class="input" value="<?= $row['besaran'] ?>"><br>
            <a href="display.php" style="text-decoration: none;">
                <button type="button" style="background-color: #687D31; color: #19350C; border: none; border-radius: 8px; padding: 5px 10px;">Cancel</button>
            </a>
            <button type="submit" style="background-color: #19350C; color: #D5D3CC; border: none; border-radius: 8px; padding: 5px 10px;">Update</button>
        </form>
    </div>
</body>
</html>