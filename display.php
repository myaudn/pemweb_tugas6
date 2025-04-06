<?php
include "koneksi.php";
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT * FROM pengeluaran";
$result = $koneksi->query($sql);

$sqltotal = "SELECT SUM(besaran) AS total_besaran FROM pengeluaran";
$result_total = $koneksi->query($sqltotal);
$total = 0;
if ($result_total && $row_total = $result_total->fetch_assoc()) {
    $total = $row_total['total_besaran'];
}

$hari_map = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];
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
</head>
<body style="background-color: #D5D3CC;">
    <div style="padding: 30px;">
        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Pengeluaran</th>
                        <th>Deskripsi</th>
                        <th>Jenis</th>
                        <th>Besaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) :
                        $hari = $row['hari'];
                        $hari_id = $hari_map[$hari];
                    ?>
                        <tr>
                        <td><?= $hari_id ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= $row['pengeluaran']?></td>
                        <td><?= $row['deskripsi']?></td>
                        <td><?= $row['jenis']?></td>
                        <td><?= $row['besaran']?></td>
                        <td>
                            <a href="update.php?id_expenses=<?= $row['id_expenses'] ?>">Edit</a> |
                            <a href="delete.php?id_expenses=<?= $row['id_expenses'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <br><p><span style="font-weight: 600; font-size: 24px;">Total Pengeluaran : <?= $total ?></span></p>

        <a href="index.html" style="text-decoration: none; color: #D5D3CC; background-color: #19350C; padding: 5px 10px; border-radius: 8px;">Tambahkan data</a>
    </div>
</body>
</html>
