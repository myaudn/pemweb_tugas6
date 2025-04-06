<?php
include "koneksi.php";

$id_expenses = $_POST['id_expenses'];
$tanggal = $_PORT['tanggal'];
$pengeluaran = $_POST['pengeluaran'];
$deskripsi = $_POST['deskripsi'];
$jenis = $_POST['jenis'];
$besaran = $_POST['besaran'];

$sql = "UPDATE pengeluaran SET
            tanggal = '$tanggal',
            pengeluaran = '$pengeluaran',
            deskripsi = '$deskripsi',
            jenis = '$jenis',
            besaran = $besaran
        WHERE id_expenses = $id_expenses";

if ($koneksi->query($sql)) {
    header("Location: display.php");
    exit;
} else {
    echo "Gagal update: " . $koneksi->error;
}
?>