<?php 
require_once 'connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$hasil = mysqli_query($conn, "SELECT *, hasil_fucom.dibuat_pada as dibuat FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan ORDER BY dibuat desc");

if (isset($_GET)) {
    if (isset($_GET['dari_tanggal'])) {
        $dari_tanggal = $_GET['dari_tanggal'];
        $sampai_tanggal = $_GET['sampai_tanggal'];
        $hasil = mysqli_query($conn, "SELECT *, hasil_fucom.dibuat_pada as dibuat FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan WHERE hasil_fucom.dibuat_pada BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY hasil_fucom.dibuat_pada desc");
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
        <h2 class="text-center">Laporan Hasil SPK Evaluasi Kinerja Karyawan</h2>
        <?php if (isset($_GET['dari_tanggal'])) : ?>
            <p class="text-right">Dari Tanggal: <?= date('d-m-Y', strtotime($dari_tanggal)); ?> Sampai Tanggal: <?= date('d-m-Y', strtotime($sampai_tanggal)); ?></p>
        <?php endif ?>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th class="text-center align-middle">No.</th>
                    <th class="text-center align-middle">Nama Karyawan</th>
                    <th class="text-center align-middle">Nilai Evaluasi Kinerja Karyawan</th>
                    <th class="text-center align-middle">Dibuat Pada</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($hasil as $dh): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $i++; ?>.</td>
                        <td class="align-middle text-start"><?= $dh['nama_karyawan']; ?></td>
                        <td class="align-middle text-start"><?= $dh['nilai_akhir']; ?></td>
                        <td class="align-middle text-start"><?= date('d-m-Y, H:i', strtotime($dh['dibuat'])); ?></td>
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
