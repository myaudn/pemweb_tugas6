<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pengeluaran = $_POST['pengeluaran'];
    $deskripsi = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];
    $besaran = $_POST['besaran'];

    include "koneksi.php";

    $sql = "INSERT INTO pengeluaran (pengeluaran, deskripsi, jenis, besaran) VALUES ('$pengeluaran', '$deskripsi', '$jenis', $besaran)";

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