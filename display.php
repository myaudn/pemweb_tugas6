<?php
include "koneksi.php";
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT * FROM pengeluaran";
$result = $koneksi->query($sql);

$sqltotalbulanan = "SELECT SUM(besaran) AS total_bulanan FROM pengeluaran WHERE tanggal >= CURDATE() - INTERVAL 1 MONTH";
$result_bulanan = $koneksi->query($sqltotalbulanan);
$totalbulanan = 0;
if ($result_bulanan && $row_bulanan = $result_bulanan->fetch_assoc()) {
    $totalbulanan = $row_bulanan['total_bulanan'];
}

$sqltotal = "SELECT SUM(besaran) AS total_besaran FROM pengeluaran";
$result_total = $koneksi->query($sqltotal);
$total = 0;
if ($result_total && $row_total = $result_total->fetch_assoc()) {
    $total = $row_total['total_besaran'];
}

$sql_jenis = "SELECT jenis, SUM(besaran) AS total FROM pengeluaran GROUP BY jenis";
$result_jenis = $koneksi->query($sql_jenis);

$labels = [];
$data = [];

while ($row_jenis = $result_jenis->fetch_assoc()) {
    $labels[] = $row_jenis['jenis'];
    $data[] = $row_jenis['total'];
}

$labels_json = json_encode($labels);
$data_json = json_encode($data);

$hari_map = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];

$sql_chart = "SELECT tanggal, SUM(besaran) AS total FROM pengeluaran GROUP BY tanggal ORDER BY tanggal ASC";
$result_chart = $koneksi->query($sql_chart);

$tanggal_array = [];
$total_array = [];

while ($row = $result_chart->fetch_assoc()) {
    $tanggal_array[] = $row['tanggal'];
    $total_array[] = $row['total'];
}

$tanggal_js = json_encode($tanggal_array);
$total_js = json_encode($total_array);
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
        #spendingChart {
            max-width: 450px;
            max-height: 450px;
            margin-top: 60px;
            margin-left: auto;
        }

        #dailyChart {
            width: 600px;
            max-height: 300px;
        }
    </style>
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
                        <td><?= number_format($row['besaran'], 0, ',', '.') ?></td>
                        <td class="act" style="display: none;">
                            <a href="update.php?id_expenses=<?= $row['id_expenses'] ?>">Edit</a> |
                            <a href="delete.php?id_expenses=<?= $row['id_expenses'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <br>
        <div style="display: flex; justify-content: space-between;">
            <button onclick="toggleActions(this)" style="text-decoration: none; color: #D5D3CC; background-color: #687D31; padding: 5px 10px; border-radius: 8px; border: none; font-family: inherit;">Edit</button>

            <a href="index.html" style="text-decoration: none; color: #D5D3CC; background-color: #19350C; padding: 5px 10px; border-radius: 8px;">Tambahkan data</a>
        </div>

        <div style="display: flex; padding: 0 60px;">
            <div>
                <br><p style="font-weight: 600; font-size: 24px; color: #19350C;">Total Pengeluaran bulan ini : <?= number_format($totalbulanan, 0, ',', '.') ?></p>
                <p style="font-weight: 600; font-size: 22px; color: #19350C;">Total Pengeluaran : <?= number_format($total, 0, ',', '.') ?></p>

                <canvas id="dailyChart" width="200" height="200"></canvas>
            </div>
        <canvas id="spendingChart" width="200" height="200"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        </div>
    </div>
    <script>
        function toggleActions(button) {
            const colact = document.querySelectorAll('.act');
            let visible = false;
            colact.forEach(td => {
                const isHidden = td.style.display === 'none';
                td.style.display = isHidden ? 'table-cell' : 'none';
                if (isHidden) visible = true;
            });
        }

        Chart.defaults.font.family = "'Poppins', sans-serif";

        const ctx = document.getElementById('spendingChart').getContext('2d');
        const spendingChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= $labels_json ?>,
                datasets: [{
                    label: 'Kategori Pengeluaran',
                    data: <?= $data_json ?>,
                    backgroundColor: ['#506F67', '#687D31','#19350C'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: "'Poppins', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            family: "'Poppins', sans-serif"
                        },
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed;
                                return context.label + ': Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        const cdx = document.getElementById('dailyChart').getContext('2d');

        const dailyChart = new Chart(cdx, {
            type: 'bar',
            data: {
                labels: <?= $tanggal_js ?>,
                datasets: [{
                    label: 'Total Pengeluaran per Hari',
                    data: <?= $total_js ?>,
                    backgroundColor: '#19350C',
                    borderWidth: 1,
                    pointBackgroundColor: '#F0F0F0',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        titleFont: { family: "'Poppins', sans-serif"},
                        bodyFont: { family: "'Poppins', sans-serif"}
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: "'Poppins', sans-serif"
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
