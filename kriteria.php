<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY peringkat_kepentingan ASC");

    $result = mysqli_query($conn, "SELECT * FROM kriteria");

    // Ubah hasil query menjadi array
    $kriteria_array = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kriteria_array[] = $row;
    }

?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Kriteria</title>
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
                            <h3 class="mb-0"><i class="nav-icon fas fa-fw fa-clipboard-list"></i> Kriteria</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Kriteria
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
                                <a href="tambah_kriteria.php" class="mb-3 btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah Kriteria</a>
                                <table class="table table-bordered" id="table_id">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center align-middle">Peringkat Kepentingan</th>
                                            <th class="text-center align-middle">Kriteria</th>
                                            <th class="text-center align-middle">Nama Kriteria</th>
                                            <th class="text-center align-middle">Bobot</th>
                                            <th class="text-center align-middle">Bobot Normalisasi</th>
                                            <th class="text-center align-middle">Dibuat Pada</th>
                                            <th class="text-center align-middle">Aksi</th>
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
                                                <td class="align-middle text-start"><?= $dk['bobot_normalisasi']; ?></td>
                                                <td class="align-middle text-start"><?= date('d-m-Y, H:i', strtotime($dk['dibuat_pada'])); ?></td>
                                                <td class="text-center align-middle">
                                                    <a href="ubah_kriteria.php?id_kriteria=<?= $dk['id_kriteria']; ?>" class="m-1 btn btn-success"><i class="fas fa-fw fa-edit"></i> Ubah</a>
                                                    <a href="hapus_kriteria.php?id_kriteria=<?= $dk['id_kriteria']; ?>" data-nama="<?= $dk['nama_kriteria']; ?>" class="m-1 btn btn-danger btn-delete"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row my-3 justify-content-center">
                                <div class="col text-center">
                                    <h5 class="fw-bold">Tingkat Kepentingan</h5>
                                    <?php foreach ($kriteria as $index => $dk): ?>
                                        K<?= $dk['kriteria_ke']; ?>
                                        <?php if ($index < mysqli_num_rows($kriteria) - 1): ?>
                                            >
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3 justify-content-center">
                                <div class="col text-center">
                                    <h5 class="fw-bold">Rasio Konsistensi Pasangan Berurut</h5>
                                    <?php foreach ($kriteria as $index => $dk): ?>
                                        <?php if ($index < count($kriteria_array) - 1): // Pastikan elemen berikutnya ada ?>
                                            Rasio 𝜙<span class="formula">_<?= $dk['kriteria_ke']; ?></span><span class="formula">_,</span><span class="formula">_<?= $kriteria_array[$index + 1]['kriteria_ke']; ?></span> = <?= $kriteria_array[$index + 1]['bobot']; ?>: K<?= $dk['kriteria_ke']; ?> <?= $kriteria_array[$index + 1]['bobot']; ?> kali lebih penting dari K<?= $kriteria_array[$index + 1]['kriteria_ke']; ?><br>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3 justify-content-center">
                                <div class="col text-center">
                                    <h5 class="fw-bold">Bobot Awal Kriteria</h5>
                                    <?php 
                                        $bobot_awal = []; // Array untuk menyimpan bobot awal
                                        $sigma_w = 0;
                                        foreach ($kriteria as $index => $dk) {
                                            if ($index == 0) {
                                                // Bobot awal untuk elemen pertama sama dengan bobotnya
                                                $bobot_awal[$index] = $dk['bobot'];
                                                $sigma_w += round($bobot_awal[$index], 4);
                                                ?>
                                                <span class="formula">w_<?= $dk['kriteria_ke']; ?></span> = <?= round($bobot_awal[$index], 4); ?><?php
                                            } else {
                                                // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                $sigma_w += round($bobot_awal[$index], 4);
                                                ?>
                                                <span class="formula">w_<?= $dk['kriteria_ke']; ?></span> = <span class="formula">\frac{w_<?= $kriteria_array[$index - 1]['kriteria_ke']; ?>}{\phi_{<?= $kriteria_array[$index - 1]['kriteria_ke']; ?>,<?= $dk['kriteria_ke']; ?>}} </span> = <?= round($bobot_awal[$index], 4); ?><?php
                                            }

                                            if ($index < count($kriteria_array) - 1) {
                                                echo ", ";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3 justify-content-center">
                                <div class="col text-center">
                                    <h5 class="fw-bold">Normalisasi Bobot</h5>
                                    <h5 class="fw-bold">Rumus:</h5>
                                    <h5 class="formula">w_i = \frac{w_i}{∑w}</h5><br>
                                    <span class="formula">∑w = <?= $sigma_w; ?></span><br><br>
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
                                                <span class="formula">w_<?= $dk['kriteria_ke']; ?></span> = <?= $final_bobot; ?><?php
                                            } else {
                                                // Bobot dihitung berdasarkan bobot sebelumnya dibagi dengan nilai bobot elemen saat ini
                                                $bobot_awal[$index] = $bobot_awal[$index - 1] / $dk['bobot'];
                                                $final_bobot = round(round($bobot_awal[$index], 4) / $sigma_w, 4);
                                                $id_kriteria = $dk['id_kriteria'];
                                                mysqli_query($conn, "UPDATE kriteria SET bobot_normalisasi = '$final_bobot' WHERE id_kriteria = '$id_kriteria'");
                                                ?>
                                                <span class="formula">w_<?= $dk['kriteria_ke']; ?></span> = <?= $final_bobot; ?><?php
                                            }

                                            if ($index < count($kriteria_array) - 1) {
                                                echo "<br>";
                                            }
                                        }
                                    ?>
                                </div>
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