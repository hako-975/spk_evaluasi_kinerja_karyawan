<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY peringkat_kepentingan ASC");
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
            $kriteria_ke = htmlspecialchars($_POST['kriteria_ke']);
            $peringkat_kepentingan = htmlspecialchars($_POST['peringkat_kepentingan']);
            $nama_kriteria = htmlspecialchars($_POST['nama_kriteria']);
            $bobot = htmlspecialchars($_POST['bobot']);

            $update_kriteria = mysqli_query($conn, "UPDATE kriteria SET kriteria_ke = '$kriteria_ke', peringkat_kepentingan = '$peringkat_kepentingan', nama_kriteria = '$nama_kriteria', bobot = '$bobot' WHERE id_kriteria = '$id_kriteria'");

            if ($update_kriteria) {
                $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY peringkat_kepentingan ASC");
                $bobot_awal = []; // Array untuk menyimpan bobot awal
                $sigma_w = 0;
                foreach ($kriteria as $index => $dk) {
                    if ($index == 0) {
                        // Bobot awal untuk elemen pertama sama dengan bobotnya
                        $bobot_awal[$index] = $dk['bobot'];
                        $sigma_w += round($bobot_awal[$index], 4);
                    } else {
                        // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                        $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                        $sigma_w += round($bobot_awal[$index], 4);
                    }
                }

                $bobot_awal = []; // Array untuk menyimpan bobot awal
                foreach ($kriteria as $index => $dk) {
                    if ($index == 0) {
                        // Bobot awal untuk elemen pertama sama dengan bobotnya
                        $bobot_awal[$index] = $dk['bobot'];
                        $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                        $id_kriteria = $dk['id_kriteria'];
                        mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                    } else {
                        // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                        $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                        $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                        $id_kriteria = $dk['id_kriteria'];
                        mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                    }
                }
                
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
                            <div class="card card-danger card-outline mb-4">
                                <form method="post" enctype="multipart/form-data"> 
                                    <div class="card-body">
                                        <div class="mb-3"> 
                                            <label for="kriteria_ke" class="form-label">Kriteria Ke (Tidak boleh sama dengan kriteria sebelumnya)</label>
                                            <input type="number" class="form-control" id="kriteria_ke" value="<?= $data_kriteria['kriteria_ke']; ?>" name="kriteria_ke" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="peringkat_kepentingan" class="form-label">Peringkat Kepentingan (Tidak boleh sama dengan kriteria sebelumnya)</label>
                                            <input type="number" class="form-control" id="peringkat_kepentingan" value="<?= $data_kriteria['peringkat_kepentingan']; ?>" name="peringkat_kepentingan" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                                            <input type="text" class="form-control" id="nama_kriteria" value="<?= $data_kriteria['nama_kriteria']; ?>" name="nama_kriteria" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="bobot" class="form-label">Bobot</label>
                                            <input type="number" min="0" step="0.001" class="form-control" id="bobot" value="<?= $data_kriteria['bobot']; ?>" name="bobot" required>
                                        </div>
                                    </div> 
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnUbahKriteria" class="btn btn-danger"><i class="fas fa-fw fa-save"></i> Submit</button>
                                    </div> 
                                </form> <!--end::Form-->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive p-2">
                                        <table class="table table-bordered" id="table_id">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="text-center align-middle">Peringkat Kepentingan</th>
                                                    <th class="text-center align-middle">Kriteria</th>
                                                    <th class="text-center align-middle">Nama Kriteria</th>
                                                    <th class="text-center align-middle">Bobot</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($kriteria as $dk): ?>
                                                    <tr>
                                                        <td class="align-middle text-center"><?= $dk['peringkat_kepentingan']; ?></td>
                                                        <td class="text-center align-middle">K<?= $dk['kriteria_ke']; ?></td>
                                                        <td class="align-middle text-start"><?= $dk['nama_kriteria']; ?></td>
                                                        <td class="align-middle text-start"><?= $dk['bobot']; ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
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