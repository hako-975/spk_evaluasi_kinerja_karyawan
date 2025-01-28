<body>
<?php 
	require 'connection.php';
 	include_once 'include/head.php';
 	include_once 'include/script.php';

	if (!isset($_SESSION['id_user'])) {
	    header("Location: login.php");
	    exit;
	}
	
	$id_kriteria = $_GET['id_kriteria'];

    $data_kriteria = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kriteria WHERE id_kriteria = '$id_kriteria'"));
    $nama_kriteria = $data_kriteria['nama_kriteria'];

	$delete_kriteria = mysqli_query($conn, "DELETE FROM kriteria WHERE id_kriteria = '$id_kriteria'");

	if ($delete_kriteria) {
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
        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Evaluasi Kinerja Karyawan $nama_kriteria berhasil dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

		echo "
	        <script>
	            Swal.fire({
	                icon: 'success',
	                title: 'Berhasil!',
	                text: 'Evaluasi Kinerja Karyawan " . $nama_kriteria . " berhasil dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'kriteria.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	} else {
        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Evaluasi Kinerja Karyawan $nama_kriteria gagal dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

	    echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'Evaluasi Kinerja Karyawan " . $nama_kriteria . " gagal dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'kriteria.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	}

?>
</body>
