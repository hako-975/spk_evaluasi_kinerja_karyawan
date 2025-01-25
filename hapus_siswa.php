<body>
<?php 
	require 'connection.php';
 	include_once 'include/head.php';
 	include_once 'include/script.php';

	if (!isset($_SESSION['id_user'])) {
	    header("Location: login.php");
	    exit;
	}
	
	$id_siswa = $_GET['id_siswa'];

    $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
    $nama_siswa = $data_siswa['nama_siswa'];

    $foto = $data_siswa['foto'];
    $image_path = 'assets/img/profiles/' . $foto;
	
	$delete_siswa = mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = '$id_siswa'");

	if ($delete_siswa) {
        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Siswa $nama_siswa berhasil dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

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
	                text: 'Siswa " . $nama_siswa . " berhasil dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'siswa.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	} else {
        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Siswa $nama_siswa gagal dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

	    echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'Siswa " . $nama_siswa . " gagal dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'siswa.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	}

?>
</body>
