<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $hasil = mysqli_query($conn, "SELECT *, hasil_topsis.dibuat_pada as dibuat FROM hasil_topsis LEFT JOIN siswa ON hasil_topsis.id_siswa = siswa.id_siswa LEFT JOIN ekskul ON hasil_topsis.id_ekskul = ekskul.id_ekskul");
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>SPK Ekstrakurikuler</title>
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
                            <h3 class="mb-0"><i class="nav-icon fas fa-fw fa-calculator"></i> SPK Ekstrakurikuler</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    SPK Ekstrakurikuler
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
                                <a href="tambah_spk.php" class="mb-3 btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah Ekstrakurikuler</a>
                                <table class="table table-bordered" id="table_id">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center align-middle">No.</th>
                                            <th class="text-center align-middle">Nama Siswa</th>
                                            <th class="text-center align-middle">Ekstrakurikuler</th>
                                            <th class="text-center align-middle">Preferensi Tertinggi</th>
                                            <th class="text-center align-middle">Dibuat Pada</th>
                                            <th class="text-center align-middle">Aksi</th>
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
                                                <td class="align-middle text-start"><?= date('d-m-Y, H:i', strtotime($dh['dibuat'])); ?></td>
                                                <td class="text-center align-middle">
                                                    <a href="hasil_spk.php?id_hasil=<?= $dh['id_hasil']; ?>" class="m-1 btn btn-primary"><i class="fas fa-fw fa-bars"></i> Detail</a>
                                                    <a href="ubah_spk.php?id_hasil=<?= $dh['id_hasil']; ?>" class="m-1 btn btn-success"><i class="fas fa-fw fa-edit"></i> Ubah</a>
                                                    <a href="hapus_spk.php?id_hasil=<?= $dh['id_hasil']; ?>" data-nama="<?= $dh['nama_siswa']; ?>" class="m-1 btn btn-danger btn-delete"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                                                </td>
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