<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $pengeluaran = $_POST['pengeluaran'];
    $deskripsi = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];
    $besaran = $_POST['besaran'];

    include "koneksi.php";

    $sql = "INSERT INTO pengeluaran (hari, tanggal, pengeluaran, deskripsi, jenis, besaran) VALUES (dayname('$tanggal'), '$tanggal', '$pengeluaran', '$deskripsi', '$jenis', $besaran)";

    if ($koneksi->query($sql)) {
        header("Location: display.php");
        exit;
    } else {
        echo "<script>
        alert('Gagal menyimpan data: " . $koneksi->error . "');
        window.location.href = 'index.php';
        </script>";
    }
}
?>