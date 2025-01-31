<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    // UPDATE KRITERIA ------------------------------------------------------------------------------------------
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
    // -------------------------------------------------------------------------------------------------

    $id_hasil = $_GET['id_hasil'];

    $data_hasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan WHERE hasil_fucom.id_hasil = '$id_hasil'"));

    if ($data_hasil == null) {
        header("Location: spk.php");
        exit;
    }

    $kriteria_ke = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kriteria_ke ASC");

    $penilaian = mysqli_query($conn, "SELECT * FROM penilaian INNER JOIN kriteria ON penilaian.id_kriteria = kriteria.id_kriteria WHERE penilaian.id_hasil = '$id_hasil' GROUP BY id_hasil");

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY peringkat_kepentingan ASC");

    $karyawan = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY nama_karyawan ASC");

    $result_kriteria = mysqli_query($conn, "SELECT * FROM kriteria");

    // Ubah hasil query menjadi array
    $kriteria_array = [];
    while ($row = mysqli_fetch_assoc($result_kriteria)) {
        $kriteria_array[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ubah SPK Evaluasi Kinerja Karyawan - <?= $data_hasil['nama_karyawan']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnUbahSpkEvaluasiKinerjaKaryawan'])) {
            $id_karyawan = htmlspecialchars($_POST['id_karyawan']);

            if ($id_karyawan == '0') {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Pilih karyawan!',
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
            foreach ($penilaian_data as $key => $nilai_data) {
                // Abaikan 'id_ekskul' karena bukan array nilai
                if (!is_array($nilai_data)) {
                    continue;
                }

                $id_kriteria = $nilai_data['id_kriteria'];
                $nilai = $nilai_data['nilai'];

                // Query insert
                $query = "INSERT INTO penilaian VALUES ('', '$id_kriteria', '$nilai', '$id_hasil')";

                if (!mysqli_query($conn, $query)) {
                    $error = true;
                    break;
                }
            }

            $nama_karyawan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'"))['nama_karyawan'];

            if (!$error) {
                $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'SPK Evaluasi Kinerja Karyawan $nama_karyawan Berhasil ditambahkan!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");
                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'SPK Evaluasi Kinerja Karyawan " . $nama_karyawan . " berhasil diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'hasil_spk.php?id_hasil=$id_hasil';
                            }
                        });
                    </script>
                ";
                exit;
            } else {
                $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Evaluasi Kinerja Karyawan $nama_karyawan gagal dihitung!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Evaluasi Kinerja Karyawan " . $nama_karyawan . " gagal diubah!'
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
                        <div class="col-sm-8">
                            <h3 class="mb-0">SPK Evaluasi Kinerja Karyawan  - <?= $data_hasil['nama_karyawan']; ?></h3>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="karyawan.php">Karyawan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    SPK Evaluasi Kinerja Karyawan
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
                            <div class="card card-danger card-outline mb-4">
                                <form method="post">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="id_karyawan" class="form-label">Nama Karyawan</label>
                                            <select name="id_karyawan" id="id_karyawan" class="form-select select2">
                                                <option value="<?= $data_hasil['id_karyawan']; ?>"><?= $data_hasil['nama_karyawan']; ?></option>
                                                <?php foreach ($karyawan as $dk): ?>
                                                    <?php if ($dk['id_karyawan'] != $data_hasil['id_karyawan']): ?>
                                                        <option value="<?= $dk['id_karyawan']; ?>"><?= htmlspecialchars($dk['nama_karyawan']); ?></option>
                                                    <?php endif ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <?php $i = 1; ?>
                                            <?php foreach ($kriteria_ke as $dk): ?>
                                                <input type="hidden" name="penilaian[<?= $dk['id_kriteria']; ?>][id_kriteria]" value="<?= $dk['id_kriteria']; ?>">
                                                <div class="mb-3 col">
                                                    <label for="nilai_<?= $dk['id_kriteria']; ?>" class="form-label">
                                                        K<?= $dk['kriteria_ke']; ?> - <?= htmlspecialchars($dk['nama_kriteria']); ?> (0-100)
                                                    </label>
                                                    <?php 
                                                        $id_kriteria = $dk['id_kriteria'];
                                                        $penilaian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penilaian WHERE id_hasil = '$id_hasil' AND id_kriteria = '$id_kriteria'"));
                                                    ?>
                                                    <input type="number" step="0.01" id="nilai_<?= $dk['id_kriteria']; ?>" class="form-control" name="penilaian[<?= $dk['id_kriteria']; ?>][nilai]" min="0" max="100" value="<?= $penilaian['nilai']; ?>" required>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnUbahSpkEvaluasiKinerjaKaryawan" class="btn btn-primary">
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