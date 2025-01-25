<body>
<?php 
	require 'connection.php';
 	include_once 'include/head.php';
 	include_once 'include/script.php';

	if (!isset($_SESSION['id_user'])) {
	    header("Location: login.php");
	    exit;
	}
	
	$id_ekskul = $_GET['id_ekskul'];

    $data_ekskul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM ekskul WHERE id_ekskul = '$id_ekskul'"));
    $nama_ekskul = $data_ekskul['nama_ekskul'];

	$delete_ekskul = mysqli_query($conn, "DELETE FROM ekskul WHERE id_ekskul = '$id_ekskul'");

	if ($delete_ekskul) {
        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Ekstrakurikuler $nama_ekskul berhasil dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

		echo "
	        <script>
	            Swal.fire({
	                icon: 'success',
	                title: 'Berhasil!',
	                text: 'Ekstrakurikuler " . $nama_ekskul . " berhasil dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'ekskul.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	} else {
        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Ekstrakurikuler $nama_ekskul gagal dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

	    echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'Ekstrakurikuler " . $nama_ekskul . " gagal dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'ekskul.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	}

?>
</body>
