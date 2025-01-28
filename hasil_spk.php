<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_hasil = $_GET['id_hasil'];

    $data_hasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hasil_fucom INNER JOIN karyawan ON hasil_fucom.id_karyawan = karyawan.id_karyawan WHERE hasil_fucom.id_hasil = '$id_hasil'"));

    if ($data_hasil == null) {
        header("Location: spk.php");
        exit;
    }

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria");

    $urutan_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY bobot DESC");

    $penilaian = mysqli_query($conn, "SELECT * FROM penilaian INNER JOIN kriteria ON penilaian.kriteria_ke = kriteria.kriteria_ke WHERE penilaian.id_hasil = '$id_hasil' GROUP BY id_hasil");
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
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (K<?= $i++; ?>)</th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($penilaian as $dp): ?>
                                                    <tr>
                                                        <?php foreach ($kriteria as $dk): ?>
                                                            <?php 
                                                                $kriteria_ke_dk = $dk['kriteria_ke'];
                                                                $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE kriteria_ke = '$kriteria_ke_dk' AND id_hasil = '$id_hasil'"));
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
                                    <?php $lastIndex = mysqli_num_rows($urutan_kriteria) - 1; // Ambil indeks terakhir array
                                    foreach ($urutan_kriteria as $index => $duk): ?>
                                        <span class="fw-bold"><?= $duk['nama_kriteria']; ?></span>
                                        <?php if ($index !== $lastIndex): ?> > <?php endif; ?>
                                    <?php endforeach; ?>
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
                                <div class="card-body">
                                    <?php $lastIndex = mysqli_num_rows($urutan_kriteria) - 1; // Ambil indeks terakhir array
                                    foreach ($urutan_kriteria as $index => $duk): ?>
                                        <?= $duk['nama_kriteria']; ?>
                                        <?php if ($index !== $lastIndex): ?> > <?php endif; ?>
                                    <?php endforeach; ?>

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (K<?= $i++; ?>)</th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <td><?= $dk['bobot']; ?></td>
                                                    <?php endforeach ?>
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
                                    <h4 class="mb-0">Hasil Matriks Normalisasi (<span class="formula">R</span>)</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                    <h4 class="formula">
                                        r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{i=1}^m x_{ij}^2}}
                                    </h4>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Alternatif</th>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (C<?= $i++; ?> - <?= $dk['atribut']; ?>)</th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($normalisasi as $row): ?>
                                                    <tr>
                                                        <td><?= $row['nama_ekskul']; ?></td>
                                                        <?php foreach ($kriteria as $dk): ?>
                                                            <td><?= number_format($row[$dk['kriteria_ke']], 5); ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
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
                                    <h4 class="mb-0">Hasil Matriks Normalisasi Ternilai (<span class="formula">V</span>)</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                     <h4 class="formula">
                                        v_{ij} = r_{ij} \cdot w_j
                                    </h4>   
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Alternatif</th>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (C<?= $i++; ?> - <?= $dk['atribut']; ?>)</th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($normalisasi as $row): ?>
                                                    <tr>
                                                        <td><?= $row['nama_ekskul']; ?></td>
                                                        <!-- Matriks Normalisasi Ternilai (V) -->
                                                        <?php foreach ($kriteria as $dk): ?>
                                                            <td><?= number_format($row[$dk['kriteria_ke']] * $dk['bobot'], 5); ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
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
                                    <h4 class="mb-0">Solusi Ideal Positif (<span class="formula">A^+</span>) dan Negatif (<span class="formula">A^-</span>)</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                     <h5 class="formula">
                                        A^+ = \{\max(v_{ij}) \text{ untuk benefit, } \min(v_{ij}) \text{ untuk cost}\}
                                    </h5>
                                    <h5 class="formula">
                                        A^- = \{\min(v_{ij}) \text{ untuk benefit, } \max(v_{ij}) \text{ untuk cost}\}
                                    </h5>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Solusi Ideal</th>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (C<?= $i++; ?> - <?= ucfirst($dk['atribut']); ?>)</th>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Solusi Ideal Positif -->
                                                <tr>
                                                    <td><span class="formula">A^+</span></td>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <?php
                                                            $kriteria_ke = $dk['kriteria_ke'];
                                                            $tipe = $dk['atribut'];
                                                            $value = $solusi_ideal_positif[$kriteria_ke];
                                                            $min_max = $tipe == 'Benefit' ? 'Max' : 'Min';
                                                        ?>
                                                        <td>
                                                            <?= number_format($value, 5); ?>
                                                            <small>(<?= $min_max; ?>)</small>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                                
                                                <!-- Solusi Ideal Negatif -->
                                                <tr>
                                                    <td><span class="formula">A^-</span></td>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <?php
                                                            $kriteria_ke = $dk['kriteria_ke'];
                                                            $tipe = $dk['atribut'];
                                                            $value = $solusi_ideal_negatif[$kriteria_ke];
                                                            $min_max = $tipe == 'Benefit' ? 'Min' : 'Max';
                                                        ?>
                                                        <td>
                                                            <?= number_format($value, 5); ?>
                                                            <small>(<?= $min_max; ?>)</small>
                                                        </td>
                                                    <?php endforeach; ?>
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
                                    <h4 class="mb-0">Jarak Matriks Normalisasi Ternilai ke Solusi Ideal <span class="formula">(D_i^+)</span> dan <span class="formula">(D_i^-)</span></h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                    <h5 class="formula">
                                        D_i^+ = \sqrt{\sum_{j=1}^n (v_{ij} - A_j^+)^2}
                                    </h5>
                                    <h5 class="formula">
                                        D_i^- = \sqrt{\sum_{j=1}^n (v_{ij} - A_j^-)^2}
                                    </h5>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Alternatif</th>
                                                    <th><span class="formula">D^+</span></th>
                                                    <th><span class="formula">D^-</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($normalisasi as $row): ?>
                                                    <tr>
                                                        <td><?= $row['nama_ekskul']; ?></td>
                                                        <!-- D+ -->
                                                        <td>
                                                            <?php $hasil_jarak_solusi = 0; ?>
                                                            <?php foreach ($kriteria as $dk): ?>
                                                                <?php 
                                                                    $kriteria_ke = $dk['kriteria_ke']; 
                                                                    $normalisasi_ternilai = number_format($row[$kriteria_ke] * $dk['bobot'], 5);
                                                                    $ideal_positif = number_format($solusi_ideal_positif[$kriteria_ke], 5);
                                                                    $diff = ($normalisasi_ternilai - $ideal_positif);
                                                                    $diff_pow = pow($diff, 2);
                                                                    $hasil_jarak_solusi += $diff_pow;
                                                                ?>
                                                            <?php endforeach; ?>
                                                            <?= number_format(sqrt($hasil_jarak_solusi), 5); ?>
                                                        </td>
                                                        <!-- D- -->
                                                        <td>
                                                            <?php $hasil_jarak_solusi = 0; ?>
                                                            <?php foreach ($kriteria as $dk): ?>
                                                                <?php 
                                                                    $kriteria_ke = $dk['kriteria_ke']; 
                                                                    $normalisasi_ternilai = number_format($row[$kriteria_ke] * $dk['bobot'], 5);
                                                                    $ideal_negatif = number_format($solusi_ideal_negatif[$kriteria_ke], 5);
                                                                    $diff = ($normalisasi_ternilai - $ideal_negatif);
                                                                    $diff_pow = pow($diff, 2);
                                                                    $hasil_jarak_solusi += $diff_pow;
                                                                ?>
                                                            <?php endforeach; ?>
                                                            <?= number_format(sqrt($hasil_jarak_solusi), 5); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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
                                    <h4 class="mb-0">Nilai Preferensi <span class="formula">(C_i)</span></h4>
                                </div>
                                <div class="card-body">
                                    <h5>Rumus:</h5>
                                    <h4 class="formula">
                                        C_i = \frac{D_i^-}{D_i^+ + D_i^-}
                                    </h4>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Alternatif</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($normalisasi as $row): ?>
                                                    <tr>
                                                        <td><?= $row['nama_ekskul']; ?></td>
                                                        <td>
                                                            <?php 
                                                                $hasil_jarak_solusi_positif = 0; 
                                                                $hasil_jarak_solusi_negatif = 0; 
                                                            ?>
                                                            <?php foreach ($kriteria as $dk): ?>
                                                                <?php 
                                                                    $kriteria_ke = $dk['kriteria_ke']; 
                                                                    $normalisasi_ternilai = number_format($row[$kriteria_ke] * $dk['bobot'], 5);

                                                                    $ideal_positif = number_format($solusi_ideal_positif[$kriteria_ke], 5);
                                                                    $diff_positif = ($normalisasi_ternilai - $ideal_positif);
                                                                    $diff_positif_pow = pow($diff_positif, 2);
                                                                    $hasil_jarak_solusi_positif += $diff_positif_pow;

                                                                    $ideal_negatif = number_format($solusi_ideal_negatif[$kriteria_ke], 5);
                                                                    $diff_negatif = ($normalisasi_ternilai - $ideal_negatif);
                                                                    $diff_negatif_pow = pow($diff_negatif, 2);
                                                                    $hasil_jarak_solusi_negatif += $diff_negatif_pow;
                                                                ?>
                                                            <?php endforeach; ?>
                                                            <?php 
                                                                $d_positif = number_format(sqrt($hasil_jarak_solusi_positif), 5); 
                                                                $d_negatif = number_format(sqrt($hasil_jarak_solusi_negatif), 5); 

                                                                $c = $d_negatif / ($d_positif + $d_negatif);
                                                            ?>
                                                            <?= number_format($c, 5); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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
                                    <h4 class="mb-0">Nilai Preferensi Tertinggi <span class="formula">(C)</span></h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Alternatif</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $top3 = []; // Array untuk menyimpan 3 tertinggi
                                                ?>
                                                <?php foreach ($normalisasi as $row): ?>
                                                    <?php 
                                                        $hasil_jarak_solusi_positif = 0; 
                                                        $hasil_jarak_solusi_negatif = 0; 
                                                    ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <?php 
                                                            $kriteria_ke = $dk['kriteria_ke']; 
                                                            $normalisasi_ternilai = number_format($row[$kriteria_ke] * $dk['bobot'], 5);

                                                            $ideal_positif = number_format($solusi_ideal_positif[$kriteria_ke], 5);
                                                            $diff_positif = ($normalisasi_ternilai - $ideal_positif);
                                                            $diff_positif_pow = pow($diff_positif, 2);
                                                            $hasil_jarak_solusi_positif += $diff_positif_pow;

                                                            $ideal_negatif = number_format($solusi_ideal_negatif[$kriteria_ke], 5);
                                                            $diff_negatif = ($normalisasi_ternilai - $ideal_negatif);
                                                            $diff_negatif_pow = pow($diff_negatif, 2);
                                                            $hasil_jarak_solusi_negatif += $diff_negatif_pow;
                                                        ?>
                                                    <?php endforeach; ?>
                                                    <?php 
                                                        $d_positif = number_format(sqrt($hasil_jarak_solusi_positif), 5); 
                                                        $d_negatif = number_format(sqrt($hasil_jarak_solusi_negatif), 5); 

                                                        $c = $d_negatif / ($d_positif + $d_negatif);

                                                        // Menyimpan hasil ke array $top3
                                                        $top3[] = [
                                                            'id_ekskul' => $row['id_ekskul'],
                                                            'nama_ekskul' => $row['nama_ekskul'],
                                                            'preferensi' => $c
                                                        ];
                                                    ?>
                                                <?php endforeach; ?>

                                                <?php 
                                                    // Mengurutkan array $top3 berdasarkan preferensi tertinggi
                                                    usort($top3, function($a, $b) {
                                                        return $b['preferensi'] <=> $a['preferensi'];
                                                    });

                                                    // Mengambil 3 data tertinggi
                                                    $top3 = array_slice($top3, 0, 3);
                                                ?>

                                                <?php 
                                                    $i = 1;
                                                    $highest = 0; 
                                                    $id_ekskul = 0;
                                                ?>
                                                <?php foreach ($top3 as $result): ?>
                                                    <?php 
                                                        if ($result['preferensi'] > $highest) {
                                                            $highest = $result['preferensi'];
                                                            $id_ekskul = $result['id_ekskul'];
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><?= $i++; ?>.</td>
                                                        <td><?= $result['nama_ekskul']; ?></td>
                                                        <td><?= number_format($result['preferensi'], 5); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>

                                                <?php 
                                                    mysqli_query($conn, "UPDATE hasil_fucom SET id_ekskul = '$id_ekskul', preferensi_tertinggi = '$highest' WHERE id_hasil = '$id_hasil'");
                                                 ?>
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