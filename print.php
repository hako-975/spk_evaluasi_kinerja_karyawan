<?php 
require_once 'connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$hasil = mysqli_query($conn, "SELECT *, hasil_topsis.dibuat_pada as dibuat FROM hasil_topsis INNER JOIN siswa ON hasil_topsis.id_siswa = siswa.id_siswa INNER JOIN ekskul ON hasil_topsis.id_ekskul = ekskul.id_ekskul ORDER BY dibuat desc");

if (isset($_GET)) {
    if (isset($_GET['dari_tanggal'])) {
        $dari_tanggal = $_GET['dari_tanggal'];
        $sampai_tanggal = $_GET['sampai_tanggal'];
        $hasil = mysqli_query($conn, "SELECT *, hasil_topsis.dibuat_pada as dibuat FROM hasil_topsis INNER JOIN siswa ON hasil_topsis.id_siswa = siswa.id_siswa INNER JOIN ekskul ON hasil_topsis.id_ekskul = ekskul.id_ekskul WHERE hasil_topsis.dibuat_pada BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY hasil_topsis.dibuat_pada desc");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .print-container {
            width: 100%;
            margin: 0 auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<?php
    if (isset($_GET['file_excel'])) {
        if ($_GET['file_excel'] == true) {
            header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=hasil_ujian_export.xls"); 
        }
    }
?>
    <div class="print-container">
        <h2 class="text-center">Laporan Hasil SPK Ekstrakurikuler</h2>
        <?php if (isset($_GET['dari_tanggal'])) : ?>
            <p class="text-right">Dari Tanggal: <?= date('d-m-Y', strtotime($dari_tanggal)); ?> Sampai Tanggal: <?= date('d-m-Y', strtotime($sampai_tanggal)); ?></p>
        <?php endif ?>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Siswa</th>
                    <th>Ekstrakurikuler</th>
                    <th>Preferensi Tertinggi</th>
                    <th>Dibuat Pada</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($hasil as $dh): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $i++; ?>.</td>
                        <td class="align-middle text-start"><?= $dh['nama_siswa']; ?></td>
                        <td class="align-middle text-start"><?= $dh['nama_ekskul']; ?></td>
                        <td class="align-middle text-start"><?= $dh['preferensi_tertinggi']; ?></td>
                        <td class="align-middle text-start"><?= date('d-m-Y, H:i \W\I\B', strtotime($dh['dibuat'])); ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <script>
        window.print()
    </script>
</body>
</html>
