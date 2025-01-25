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

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY nama_kriteria ASC");
    $penilaian = mysqli_query($conn, "SELECT * FROM penilaian INNER JOIN kriteria ON penilaian.id_kriteria = kriteria.id_kriteria INNER JOIN ekskul ON penilaian.id_ekskul = ekskul.id_ekskul WHERE penilaian.id_hasil = '$id_hasil' GROUP BY penilaian.id_ekskul ORDER BY ekskul.nama_ekskul ASC");

    if ($data_hasil == null) {
        header("Location: spk.php");
        exit;
    }

    // ------------------- NORMALISASI MATRIKS KEPUTUSAN (R) --------------------------------------------
    // Inisialisasi variabel
    $normalisasi = [];
    $kolom_total = [];

    // Step 1: Hitung total kuadrat per kriteria
    foreach ($kriteria as $dk) {
        $id_kriteria_dk = $dk['id_kriteria'];
        $total_kuadrat = 0;

        foreach ($penilaian as $dp) {
            $id_ekskul_dp = $dp['id_ekskul'];
            $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria = '$id_kriteria_dk' AND id_ekskul = '$id_ekskul_dp' AND id_hasil = '$id_hasil'"));
            $total_kuadrat += pow($nilai['nilai'], 2); // Hitung kuadrat nilai
        }

        $kolom_total[$id_kriteria_dk] = sqrt($total_kuadrat); // Simpan akar total kuadrat
    }

    // Step 2: Hitung normalisasi
    foreach ($penilaian as $dp) {
        $id_ekskul_dp = $dp['id_ekskul'];
        $row_normalisasi = ['id_ekskul' => $dp['id_ekskul'], 'nama_ekskul' => $dp['nama_ekskul']];

        foreach ($kriteria as $dk) {
            $id_kriteria_dk = $dk['id_kriteria'];
            $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria = '$id_kriteria_dk' AND id_ekskul = '$id_ekskul_dp' AND id_hasil = '$id_hasil'"));
            $nilai_normalisasi = $nilai['nilai'] / $kolom_total[$id_kriteria_dk]; // Hitung normalisasi
            $row_normalisasi[$id_kriteria_dk] = $nilai_normalisasi;
        }

        $normalisasi[] = $row_normalisasi;
    }

    // ------------------- Solusi Ideal Positif dan Negatif --------------------------------------------
    // Inisialisasi Solusi Ideal
    $solusi_ideal_positif = [];
    $solusi_ideal_negatif = [];

    // Matriks Ternilai
    $matriks_ternilai = [];

    // Step 1: Hitung Matriks Ternilai (V)
    foreach ($normalisasi as $row) {
        $row_ternilai = [];
        foreach ($kriteria as $dk) {
            $id_kriteria = $dk['id_kriteria'];
            $row_ternilai[$id_kriteria] = $row[$id_kriteria] * $dk['bobot']; // V_ij = r_ij * w_j
        }
        $matriks_ternilai[] = $row_ternilai;
    }

    // Step 2: Hitung Solusi Ideal Positif dan Negatif
    foreach ($kriteria as $dk) {
        $id_kriteria = $dk['id_kriteria'];
        $tipe = $dk['atribut']; // 'benefit' atau 'cost'
        
        // $solusi_ideal_positif['tipe'] = $tipe;
        // $solusi_ideal_negatif['tipe'] = $tipe;

        $values = array_column($matriks_ternilai, $id_kriteria);
        if ($tipe == 'Benefit') {
            $solusi_ideal_positif[$id_kriteria] = max($values);
            $solusi_ideal_negatif[$id_kriteria] = min($values);
        } else { // cost
            $solusi_ideal_positif[$id_kriteria] = min($values);
            $solusi_ideal_negatif[$id_kriteria] = max($values);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasil SPK Ekstrakurikuler - <?= $data_hasil['nama_siswa']; ?></title>
    <?php include_once 'include/head.php'; ?>
    <!-- KaTeX CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.13.18/dist/katex.min.css">
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
                        <div class="col-sm-6">
                            <h3 class="mb-0">Hasil SPK Ekstrakurikuler - <?= $data_hasil['nama_siswa']; ?></h3>
                        </div>
                        <div class="col-sm-6">
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
                            <div class="card card-primary card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Data Awal</h4>
                                </div>
                                <div class="card-body">
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
                                                <?php foreach ($penilaian as $dp): ?>
                                                    <tr>
                                                        <td><?= $dp['nama_ekskul']; ?></td>
                                                        <?php foreach ($kriteria as $dk): ?>
                                                            <?php 
                                                                $id_kriteria_dk = $dk['id_kriteria'];
                                                                $id_ekskul_dp = $dp['id_ekskul'];
                                                                $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria = '$id_kriteria_dk' AND id_ekskul = '$id_ekskul_dp' AND id_hasil = '$id_hasil'"));
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
                            <div class="card card-primary card-outline mb-4">
                                <div class="card-header text-center">
                                    <h4 class="mb-0">Bobot Kriteria (<span class="formula">w</span>)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($kriteria as $dk): ?>
                                                        <th><?= $dk['nama_kriteria']; ?> (C<?= $i++; ?> - <?= $dk['atribut']; ?>)</th>
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
                            <div class="card card-primary card-outline mb-4">
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
                                                            <td><?= number_format($row[$dk['id_kriteria']], 5); ?></td>
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
                            <div class="card card-primary card-outline mb-4">
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
                                                            <td><?= number_format($row[$dk['id_kriteria']] * $dk['bobot'], 5); ?></td>
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
                            <div class="card card-primary card-outline mb-4">
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
                                                            $id_kriteria = $dk['id_kriteria'];
                                                            $tipe = $dk['atribut'];
                                                            $value = $solusi_ideal_positif[$id_kriteria];
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
                                                            $id_kriteria = $dk['id_kriteria'];
                                                            $tipe = $dk['atribut'];
                                                            $value = $solusi_ideal_negatif[$id_kriteria];
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
                            <div class="card card-primary card-outline mb-4">
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
                                                                    $id_kriteria = $dk['id_kriteria']; 
                                                                    $normalisasi_ternilai = number_format($row[$id_kriteria] * $dk['bobot'], 5);
                                                                    $ideal_positif = number_format($solusi_ideal_positif[$id_kriteria], 5);
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
                                                                    $id_kriteria = $dk['id_kriteria']; 
                                                                    $normalisasi_ternilai = number_format($row[$id_kriteria] * $dk['bobot'], 5);
                                                                    $ideal_negatif = number_format($solusi_ideal_negatif[$id_kriteria], 5);
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
                            <div class="card card-primary card-outline mb-4">
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
                                                                    $id_kriteria = $dk['id_kriteria']; 
                                                                    $normalisasi_ternilai = number_format($row[$id_kriteria] * $dk['bobot'], 5);

                                                                    $ideal_positif = number_format($solusi_ideal_positif[$id_kriteria], 5);
                                                                    $diff_positif = ($normalisasi_ternilai - $ideal_positif);
                                                                    $diff_positif_pow = pow($diff_positif, 2);
                                                                    $hasil_jarak_solusi_positif += $diff_positif_pow;

                                                                    $ideal_negatif = number_format($solusi_ideal_negatif[$id_kriteria], 5);
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
                            <div class="card card-primary card-outline mb-4">
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
                                                            $id_kriteria = $dk['id_kriteria']; 
                                                            $normalisasi_ternilai = number_format($row[$id_kriteria] * $dk['bobot'], 5);

                                                            $ideal_positif = number_format($solusi_ideal_positif[$id_kriteria], 5);
                                                            $diff_positif = ($normalisasi_ternilai - $ideal_positif);
                                                            $diff_positif_pow = pow($diff_positif, 2);
                                                            $hasil_jarak_solusi_positif += $diff_positif_pow;

                                                            $ideal_negatif = number_format($solusi_ideal_negatif[$id_kriteria], 5);
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
                                                    mysqli_query($conn, "UPDATE hasil_topsis SET id_ekskul = '$id_ekskul', preferensi_tertinggi = '$highest' WHERE id_hasil = '$id_hasil'");
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
    <!-- KaTeX JS -->
    <script src="js/katex.js"></script>
    <!-- KaTeX Auto-Render JS -->
    <script>
        $(document).ready(function() {
            // Render LaTeX inside elements with id "formula"
            $('.formula').each(function() {
                var formula = $(this).html(); // Get the LaTeX string
                // Render the LaTeX string using KaTeX
                katex.render(formula, this);
            });
        });
    </script>
</body><!--end::Body-->

</html>