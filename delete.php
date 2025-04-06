<?php
include "koneksi.php";
$id_expenses = $_GET['id_expenses'];

$sql = "DELETE FROM pengeluaran WHERE id_expenses = $id_expenses";

if ($koneksi->query($sql)) {
    header("Location: display.php");
    exit;
} else {
    echo "Gagal hapus: " . $koneksi->error;
}
?>