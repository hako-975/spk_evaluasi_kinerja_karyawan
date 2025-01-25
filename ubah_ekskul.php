<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_ekskul = $_GET['id_ekskul'];
    $data_ekskul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM ekskul WHERE id_ekskul = '$id_ekskul'"));
    if ($data_ekskul == null) {
        header("Location: ekskul.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ubah Ekstrakurikuler - <?= $data_ekskul['nama_ekskul']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnUbahEkstrakurikuler'])) {
            $nama_ekskul = htmlspecialchars($_POST['nama_ekskul']);

            $update_ekskul = mysqli_query($conn, "UPDATE ekskul SET nama_ekskul = '$nama_ekskul' WHERE id_ekskul = '$id_ekskul'");

            if ($update_ekskul) {
                $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Ekstrakurikuler $nama_ekskul berhasil diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Ekstrakurikuler " . $nama_ekskul . " berhasil diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'ekskul.php';
                            }
                        });
                    </script>
                ";
                exit;
            } else {
                $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Ekstrakurikuler $nama_ekskul gagal diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Ekstrakurikuler " . $nama_ekskul . " gagal diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.history.back();
                            }
                        });
                    </script>
                ";
                exit;
            }
        }
    ?>
    <div class="app-wrapper"> <!--begin::Header-->
        <?php include_once 'include/navbar.php'; ?>
        <?php include_once 'include/sidebar.php'; ?>
        <!--begin::App Main-->
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content-header"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Ubah Ekstrakurikuler - <?= $data_ekskul['nama_ekskul']; ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="ekskul.php">Ekstrakurikuler</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Ubah Ekstrakurikuler
                                </li>
                            </ol>
                        </div>
                    </div> <!--end::Row-->
                </div> <!--end::Container-->
            </div>
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!-- Info boxes -->
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-primary card-outline mb-4">
                                <form method="post" enctype="multipart/form-data"> 
                                    <div class="card-body">
                                        <div class="mb-3"> 
                                            <label for="nama_ekskul" class="form-label">Nama Ekstrakurikuler</label>
                                            <input type="text" class="form-control" id="nama_ekskul" name="nama_ekskul" value="<?= $data_ekskul['nama_ekskul']; ?>" required>
                                        </div>
                                    </div> 
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnUbahEkstrakurikuler" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Submit</button>
                                    </div> 
                                </form> <!--end::Form-->
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