<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_hasil = $_GET['id_hasil'];

    $data_hasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hasil_topsis INNER JOIN siswa ON hasil_topsis.id_siswa = siswa.id_siswa WHERE hasil_topsis.id_hasil = '$id_hasil'"));

    if ($data_hasil == null) {
        header("Location: spk.php");
        exit;
    }

    $siswa = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama_siswa ASC");
    $ekskul = mysqli_query($conn, "SELECT * FROM ekskul ORDER BY nama_ekskul ASC");
    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY nama_kriteria ASC");

    if (isset($_GET['id_siswa'])) {
        $id_siswa = $_GET['id_siswa'];
        $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ubah SPK Ekstrakurikuler</title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnSpkEkstrakurikuler'])) {
            $id_siswa = htmlspecialchars($_POST['id_siswa']);

            if ($id_siswa == '0') {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Pilih siswa!',
                            confirmButtonText: 'Kembali'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.history.back();
                            }
                        });
                    </script>
                ";
                exit;
            }

            $penilaian_data = $_POST['penilaian'];
            $error = false;
            mysqli_query($conn, "DELETE FROM penilaian WHERE id_hasil = '$id_hasil'");
            foreach ($penilaian_data as $id => $data) {
                $id_ekskul = $data['id_ekskul'];
                foreach ($data as $key => $nilai_data) {
                    // Abaikan 'id_ekskul' karena bukan array nilai
                    if (!is_array($nilai_data)) {
                        continue;
                    }

                    $id_kriteria = $nilai_data['id_kriteria'];
                    $nilai = $nilai_data['nilai'];

                    // Query insert
                    $query = "INSERT INTO penilaian VALUES ('', '$id_kriteria', '$id_ekskul', '$nilai', '$id_hasil')";

                    if (!mysqli_query($conn, $query)) {
                        $error = true;
                        break;
                    }
                }
            }

            $nama_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"))['nama_siswa'];

            if (!$error) {
                $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'SPK Ekstrakurikuler $nama_siswa Berhasil diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");
                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'SPK Ekstrakurikuler " . $nama_siswa . " berhasil diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'hasil_spk.php?id_hasil=$id_hasil';
                            }
                        });
                    </script>
                ";
                exit;
            } else {
                $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Ekstrakurikuler $nama_siswa gagal dihitung!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Ekstrakurikuler " . $nama_siswa . " gagal diubah!'
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
                            <h3 class="mb-0">SPK Ekstrakurikuler</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="siswa.php">Siswa</a></li>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary card-outline mb-4">
                                <form method="post">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="id_siswa" class="form-label">Nama Siswa</label>
                                            <select name="id_siswa" id="id_siswa" class="form-select select2">
                                                <option value="<?= $data_hasil['id_siswa']; ?>"><?= $data_hasil['nama_siswa']; ?></option>
                                                <?php foreach ($siswa as $ds): ?>
                                                    <?php if ($data_hasil['id_siswa'] != $ds['id_siswa']): ?>
                                                        <option value="<?= $ds['id_siswa']; ?>"><?= htmlspecialchars($ds['nama_siswa']); ?></option>
                                                    <?php endif ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <hr>
                                        <?php foreach ($ekskul as $de): ?>
                                            <input type="hidden" name="penilaian[<?= $de['id_ekskul']; ?>][id_ekskul]" value="<?= $de['id_ekskul']; ?>">
                                            <div class="row">
                                                <?php foreach ($kriteria as $dk): ?>
                                                    <input type="hidden" name="penilaian[<?= $de['id_ekskul']; ?>][<?= $dk['id_kriteria']; ?>][id_kriteria]" value="<?= $dk['id_kriteria']; ?>">
                                                    <div class="mb-3 col">
                                                        <label for="nilai_<?= $de['id_ekskul']; ?>_<?= $dk['id_kriteria']; ?>" class="form-label">
                                                            <?= htmlspecialchars($dk['nama_kriteria']); ?> Ekskul <?= htmlspecialchars($de['nama_ekskul']); ?> (0-10)
                                                        </label>
                                                        <?php 
                                                            $id_ekskul = $de['id_ekskul'];
                                                            $id_kriteria = $dk['id_kriteria'];
                                                            $penilaian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penilaian WHERE id_hasil = '$id_hasil' AND id_ekskul = '$id_ekskul' AND id_kriteria = '$id_kriteria'"));
                                                        ?>
                                                        <input type="number" step="0.01" id="nilai_<?= $de['id_ekskul']; ?>_<?= $dk['id_kriteria']; ?>" class="form-control" name="penilaian[<?= $de['id_ekskul']; ?>][<?= $dk['id_kriteria']; ?>][nilai]" min="0" max="10" value="<?= $penilaian['nilai']; ?>" required>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <hr>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnSpkEkstrakurikuler" class="btn btn-primary">
                                            <i class="fas fa-fw fa-save"></i> Submit
                                        </button>
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