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


    $result_kriteria = mysqli_query($conn, "SELECT * FROM kriteria");

    // Ubah hasil query menjadi array
    $kriteria_array = [];
    while ($row = mysqli_fetch_assoc($result_kriteria)) {
        $kriteria_array[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasil SPK Evaluasi Kinerja Karyawan - <?= $data_hasil['nama_karyawan']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper"> <!--begin::Header-->
        <?php include_once 'include/navbar.php'; ?>
        <?php include_once 'include/sidebar.php'; ?>
        <!--begin::App Main-->
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content-header"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-8">
                            <h3 class="mb-0">Hasil SPK Evaluasi Kinerja Karyawan - <?= $data_hasil['nama_karyawan']; ?></h3>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="spk.php">SPK</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Hasil SPK
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
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Data Awal</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria_ke as $dk): ?>
                                                        <td class="text-center">K<?= $dk['kriteria_ke']; ?> - <?= $dk['nama_kriteria']; ?></td>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($penilaian as $dp): ?>
                                                    <tr>
                                                        <?php foreach ($kriteria_ke as $dk): ?>
                                                            <?php 
                                                                $id_kriteria_dk = $dk['id_kriteria'];
                                                                $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria = '$id_kriteria_dk' AND id_hasil = '$id_hasil'"));
                                                            ?>
                                                            <td><?= $nilai['nilai']; ?></td>
                                                        <?php endforeach ?>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody> 
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Penentuan Tingkat Kepentingan</h4>
                                </div>
                                <div class="card-body text-center">
                                    <?php foreach ($kriteria as $index => $dk): ?>
                                        <span>K<?= $dk['kriteria_ke']; ?> - <?= $dk['nama_kriteria']; ?></span>
                                        <?php if ($index < mysqli_num_rows($kriteria) - 1): ?>
                                            >
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Rasio Konsistensi Pasangan Berurut</h4>
                                </div>
                                <div class="card-body text-center">
                                    <?php foreach ($kriteria as $index => $dk): ?>
                                        <?php if ($index < count($kriteria_array) - 1): // Pastikan elemen berikutnya ada ?>
                                            Rasio 𝜙<span class="formula">_<?= $dk['kriteria_ke']; ?></span><span class="formula">_,</span><span class="formula">_<?= $kriteria_array[$index + 1]['kriteria_ke']; ?></span> = <?= $kriteria_array[$index + 1]['bobot']; ?>: K<?= $dk['kriteria_ke']; ?> <?= $kriteria_array[$index + 1]['bobot']; ?> kali lebih penting dari K<?= $kriteria_array[$index + 1]['kriteria_ke']; ?><br>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Bobot Awal Kriteria</h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                <?php 
                                                    $bobot_awal = []; // Array untuk menyimpan bobot awal
                                                    $sigma_w = 0;
                                                    foreach ($kriteria as $index => $dk) {
                                                        if ($index == 0) {
                                                            // Bobot awal untuk elemen pertama sama dengan bobotnya
                                                            $bobot_awal[$index] = $dk['bobot'];
                                                            $sigma_w += round($bobot_awal[$index], 4);
                                                            ?>
                                                            <td class="text-center"><span class="formula">w_<?= $dk['kriteria_ke']; ?></span></td><?php
                                                        } else {
                                                            // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                            $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                            $sigma_w += round($bobot_awal[$index], 4);
                                                            ?>
                                                            <td class="text-center"><span class="formula">w_<?= $dk['kriteria_ke']; ?></span> = <span class="formula">\frac{w_<?= $kriteria_array[$index - 1]['kriteria_ke']; ?>}{\phi_{<?= $kriteria_array[$index - 1]['kriteria_ke']; ?>,<?= $dk['kriteria_ke']; ?>}} </span></td><?php
                                                        }
                                                    }
                                                ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                <?php 
                                                    $bobot_awal = []; // Array untuk menyimpan bobot awal
                                                    $sigma_w = 0;
                                                    foreach ($kriteria as $index => $dk) {
                                                        if ($index == 0) {
                                                            // Bobot awal untuk elemen pertama sama dengan bobotnya
                                                            $bobot_awal[$index] = $dk['bobot'];
                                                            $sigma_w += round($bobot_awal[$index], 4);
                                                            ?>
                                                            <td><?= round($bobot_awal[$index], 4); ?></td><?php
                                                        } else {
                                                            // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                            $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                            $sigma_w += round($bobot_awal[$index], 4);
                                                            ?>
                                                            <td><?= round($bobot_awal[$index], 4); ?></td><?php
                                                        }
                                                    }
                                                ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Normalisasi Bobot</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                    <h5 class="formula">w_i = \frac{w_i}{∑w}</h5>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><span class="formula">∑w</span></th>
                                                    <?php 
                                                        $bobot_awal = []; // Array untuk menyimpan bobot awal
                                                        foreach ($kriteria as $index => $dk) {
                                                            if ($index == 0) {
                                                                // Bobot awal untuk elemen pertama sama dengan bobotnya
                                                                $bobot_awal[$index] = $dk['bobot'];
                                                                $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                                                                $id_kriteria = $dk['id_kriteria'];
                                                                mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                                                                ?>
                                                                <th class="text-center"><span class="formula">w_<?= $dk['kriteria_ke']; ?></span></th><?php
                                                            } else {
                                                                // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                                $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                                $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                                                                $id_kriteria = $dk['id_kriteria'];
                                                                mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                                                                ?>
                                                                <th class="text-center"><span class="formula">w_<?= $dk['kriteria_ke']; ?></span></th><?php

                                                            }
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?= $sigma_w; ?></td>
                                                    <?php 
                                                        $bobot_awal = []; // Array untuk menyimpan bobot awal
                                                        foreach ($kriteria as $index => $dk) {
                                                            if ($index == 0) {
                                                                // Bobot awal untuk elemen pertama sama dengan bobotnya
                                                                $bobot_awal[$index] = $dk['bobot'];
                                                                $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                                                                $id_kriteria = $dk['id_kriteria'];
                                                                mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                                                                ?>
                                                                <td><?= $final_bobot; ?></td><?php
                                                            } else {
                                                                // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                                $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                                $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                                                                $id_kriteria = $dk['id_kriteria'];
                                                                mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                                                                ?>
                                                                <td><?= $final_bobot; ?></td><?php

                                                            }
                                                        }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Nilai Akhir</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                    <h5 class="formula">A = (x_1×w_1)+(x_2×w_2)+(x_3×w_3)+⋯+(x_n×w_n)</h5>
                                    <hr>
                                    <h5>
                                        A = 
                                        <?php $hasil_akhir = 0; ?>
                                        <?php foreach ($penilaian as $dp): ?>
                                            <?php 
                                                $last_index = array_key_last($kriteria_array); // Dapatkan indeks terakhir
                                            ?>
                                            <?php foreach ($kriteria_ke as $index => $dk): ?>
                                                <?php 
                                                    $id_kriteria_dk = $dk['id_kriteria'];
                                                    $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria = '$id_kriteria_dk' AND id_hasil = '$id_hasil'"));
                                                    $hasil_akhir += ($nilai['nilai'] * $dk['bobot_normalisasi']);
                                                ?>
                                                <td>
                                                    (<?= $nilai['nilai']; ?> x <?= $dk['bobot_normalisasi']; ?>)
                                                    <?= ($index !== $last_index) ? '+' : ''; ?>
                                                </td>
                                            <?php endforeach ?>
                                        <?php endforeach ?>
                                        = <?= $hasil_akhir; ?>
                                        <?php mysqli_query($conn, "UPDATE hasil_fucom SET nilai_akhir = '$hasil_akhir' WHERE id_hasil = '$id_hasil'"); ?>
                                    </h5>
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