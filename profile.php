<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Profile - Sistem Pendukung Keputusan Ekstrakurikuler Fuzzy Tsukamoto</title>
    <?php include_once 'include/head.php'; ?>
    <style>
        .profile-card {
            max-width: 400px;
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
                        <div class="col-sm-6">
                            <h3 class="mb-0">Profile</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Profile
                                </li>
                            </ol>
                        </div>
                    </div> <!--end::Row-->
                </div> <!--end::Container-->
            </div>
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid">
                    <div class="profile-card mb-3 card card-outline card-primary">
                        <div class="text-center">
                            <img src="assets/img/profiles/<?= $dataUser['foto']; ?>" alt="Profile Picture">
                        </div>
                        <h3 class="text-center"><?= $dataUser['nama']; ?></h3>
                        <p><strong>Username: </strong><?= $dataUser['username']; ?></p>
                        <p><strong>Jabatan: </strong><?= ucwords($dataUser['jabatan']); ?></p>
                        <p><strong>Dibuat Pada: </strong><?= date('d-m-Y, H:i:s', strtotime($dataUser['dibuat_pada']));; ?></p>
                        <div class="btn-group" role="group">
                            <a href="ubah_profile.php" class="btn btn-success"><i class="fas fa-fw fa-edit"></i> Ubah Profile</a>
                            <a href="ganti_password.php" class="btn btn-danger"><i class="fas fa-fw fa-lock"></i> Ganti Password</a>
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