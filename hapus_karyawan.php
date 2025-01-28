<body>
<?php 
	require 'connection.php';
 	include_once 'include/head.php';
 	include_once 'include/script.php';

	if (!isset($_SESSION['id_user'])) {
	    header("Location: login.php");
	    exit;
	}
	
	$id_karyawan = $_GET['id_karyawan'];

    $data_karyawan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'"));
    $nama_karyawan = $data_karyawan['nama_karyawan'];

    $foto = $data_karyawan['foto'];
    $image_path = 'assets/img/profiles/' . $foto;
	
	$delete_karyawan = mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan = '$id_karyawan'");

	if ($delete_karyawan) {
        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Karyawan $nama_karyawan berhasil dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

		if ($foto != 'default.jpg' && $foto != '') {
		    if (file_exists($image_path)) {
		        unlink($image_path);
		    }
		}

		echo "
	        <script>
	            Swal.fire({
	                icon: 'success',
	                title: 'Berhasil!',
	                text: 'Karyawan " . $nama_karyawan . " berhasil dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'karyawan.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	} else {
        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Karyawan $nama_karyawan gagal dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

	    echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'Karyawan " . $nama_karyawan . " gagal dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'karyawan.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	}

?>
</body>
