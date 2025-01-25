<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_siswa = $_GET['id_siswa'];

    $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Detail Siswa - <?= $data_siswa['nama_siswa']; ?></title>
    <?php include_once 'include/head.php'; ?>
    <style>
        .profile-card {
            width: 100%;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .profile-card img {
            width: 200px;
            height: auto;
            border-radius: 10px;
        }
        .profile-card h3 {
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .profile-card p {
            color: #666;
            margin-bottom: 10px;
        }
        .profile-card .btn-group {
            width: 100%;
        }
        .profile-card .btn-group .btn {
            width: 50%;
        }
    </style>
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
                        <div class="col-sm-8">
                            <h3 class="mb-0"><i class="nav-icon fas fa-fw fa-user"></i> Detail Siswa - <?= $data_siswa['nama_siswa']; ?></h3>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="siswa.php">Siswa</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Detail Siswa
                                </li>
                            </ol>
                        </div>
                    </div> <!--end::Row-->
                </div> <!--end::Container-->
            </div>
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!-- Info boxes -->
                    <div class="row">
                        <div class="col-5">
                            <div class="profile-card mb-3">
                                <div class="text-center">
                                    <img src="assets/img/profiles/<?= $data_siswa['foto']; ?>" alt="<?= $data_siswa['foto']; ?>">
                                </div>
                                <h3 class="text-center"><?= $data_siswa['nama_siswa']; ?></h3>
                                <p><strong>Tanggal Lahir: </strong><?= date('d-m-Y', strtotime($data_siswa['tanggal_lahir']));; ?></p>
                                <p><strong>Jenis Kelamin: </strong><?= ucwords($data_siswa['jenis_kelamin']); ?></p>
                                <p><strong>No. Telepon: </strong><?= $data_siswa['no_hp']; ?></p>
                                <p><strong>Alamat: </strong><?= $data_siswa['alamat']; ?></p>
                                <p><strong>Kelas Siswa: </strong><?= $data_siswa['kelas_siswa']; ?></p>
                                <p><strong>Dibuat Pada: </strong><?= date('d-m-Y, H:i:s', strtotime($data_siswa['dibuat_pada']));; ?></p>
                                <div class="btn-group" role="group">
                                    <a href="ubah_siswa.php?id_siswa=<?= $data_siswa['id_siswa']; ?>" class="btn btn-success"><i class="fas fa-fw fa-edit"></i> Ubah</a>
                                    <a href="hapus_siswa.php?id_siswa=<?= $data_siswa['id_siswa']; ?>" data-nama="<?= $data_siswa['nama_siswa']; ?>" class="btn btn-danger btn-delete"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> 
        <?php include_once 'include/footer.php'; ?>
    </div> <!--end::App Wrapper--> 
    <?php include_once 'include/script.php'; ?>
    
</body><!--end::Body-->

</html>