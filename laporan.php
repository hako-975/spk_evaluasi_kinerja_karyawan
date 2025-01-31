<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $hasil = mysqli_query($conn, "SELECT *, hasil_fucom.dibuat_pada as dibuat FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan ORDER BY dibuat desc");
    
    if (isset($_GET['btnPrint'])) {
        $dari_tanggal = $_GET['dari_tanggal'] . ' 00:00:00';
        $sampai_tanggal = $_GET['sampai_tanggal'] . ' 23:59:59';
        $hasil = mysqli_query($conn, "SELECT *, hasil_fucom.dibuat_pada as dibuat FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan WHERE hasil_fucom.dibuat_pada BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY hasil_fucom.dibuat_pada desc");
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Laporan Hasil SPK Evaluasi Kinerja Karyawan</title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <?php include_once 'include/navbar.php'; ?>
        <?php include_once 'include/sidebar.php'; ?>
        <!--begin::App Main-->
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content-header"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><i class="nav-icon fas fa-fw fa-file-alt"></i> Laporan Hasil SPK Evaluasi Kinerja Karyawan</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Laporan Hasil SPK Evaluasi Kinerja Karyawan
                                </li>
                            </ol>
                        </div>
                    </div> <!--end::Row-->
                </div> <!--end::Container-->
            </div>
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!-- Info boxes -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive p-2">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-3"> 
                                            <label for="dari_tanggal" class="form-label">Dari Tanggal</label>
                                            <input type="date" value="<?= (isset($_GET['dari_tanggal']) ? $_GET['dari_tanggal'] : date('Y-m-01')); ?>" class="form-control" id="dari_tanggal" name="dari_tanggal" required>
                                        </div>
                                        <div class="col-3"> 
                                            <label for="sampai_tanggal" class="form-label">Sampai Tanggal</label>
                                            <input type="date" value="<?= (isset($_GET['sampai_tanggal']) ? $_GET['sampai_tanggal'] : date('Y-m-d')); ?>" class="form-control" id="sampai_tanggal" name="sampai_tanggal" required>
                                        </div>
                                        <div class="col-6 d-flex align-items-end"> 
                                            <button type="submit" name="btnPrint" class="me-3 btn btn-primary"><i class="fas fa-fw fa-filter"></i> filter</button>
                                            <a href="laporan.php" class="me-3 btn btn-danger"><i class="fas fa-fw fa-times"></i> Reset</a>
                                            <?php if (isset($_GET['btnPrint'])): ?>
                                                <a href="print.php?dari_tanggal=<?= $dari_tanggal; ?>&sampai_tanggal=<?= $sampai_tanggal; ?>" target="_blank" class="me-3 btn btn-success"><i class="fas fa-fw fa-print"></i> Print</a>
                                                <a href="print.php?dari_tanggal=<?= $dari_tanggal; ?>&sampai_tanggal=<?= $sampai_tanggal; ?>&file_excel=true" target="_blank" class="me-3 btn btn-success"><i class="fas fa-fw fa-file-excel"></i> Excel</a>
                                            <?php else: ?>
                                                <a href="print.php" target="_blank" class="me-3 btn btn-success"><i class="fas fa-fw fa-print"></i> Print</a>
                                                <a href="print.php?file_excel=true" target="_blank" class="me-3 btn btn-success"><i class="fas fa-fw fa-file-excel"></i> Excel</a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <table class="table table-bordered" id="table_id">
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
                        </div>
                    </div> <!-- /.row --> <!--begin::Row-->
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> 
        <?php include_once 'include/footer.php'; ?>
    </div> <!--end::App Wrapper--> 
    <?php include_once 'include/script.php'; ?>
</body><!--end::Body-->

</html>