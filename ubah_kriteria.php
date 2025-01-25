<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_kriteria = $_GET['id_kriteria'];
    $data_kriteria = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kriteria WHERE id_kriteria = '$id_kriteria'"));
    if ($data_kriteria == null) {
        header("Location: kriteria.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ubah Kriteria - <?= $data_kriteria['nama_kriteria']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnUbahKriteria'])) {
            $nama_kriteria = htmlspecialchars($_POST['nama_kriteria']);
            $bobot = htmlspecialchars($_POST['bobot']);
            $atribut = htmlspecialchars($_POST['atribut']);

            $update_kriteria = mysqli_query($conn, "UPDATE kriteria SET nama_kriteria = '$nama_kriteria', bobot = '$bobot', atribut = '$atribut' WHERE id_kriteria = '$id_kriteria'");

            if ($update_kriteria) {
                $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Kriteria $nama_kriteria berhasil diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Kriteria " . $nama_kriteria . " berhasil diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'kriteria.php';
                            }
                        });
                    </script>
                ";
                exit;
            } else {
                $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Kriteria $nama_kriteria gagal diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Kriteria " . $nama_kriteria . " gagal diubah!'
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
                            <h3 class="mb-0">Ubah Kriteria - <?= $data_kriteria['nama_kriteria']; ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="kriteria.php">Kriteria</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Ubah Kriteria
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
                                            <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="<?= $data_kriteria['nama_kriteria']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="bobot" class="form-label">Bobot</label>
                                            <input type="number" min="0" step="0.1" class="form-control" id="bobot" name="bobot" value="<?= $data_kriteria['bobot']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="atribut" class="form-label">Atribut</label>
                                            <select name="atribut" id="atribut" class="form-select" required>
                                                <?php if ($data_kriteria['atribut'] == 'Benefit'): ?>
                                                    <option value="Benefit">Benefit</option>
                                                    <option value="Cost">Cost</option>
                                                <?php else: ?>
                                                    <option value="Cost">Cost</option>
                                                    <option value="Benefit">Benefit</option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnUbahKriteria" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Submit</button>
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