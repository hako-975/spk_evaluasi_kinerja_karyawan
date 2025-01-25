<body>
<?php 
	require 'connection.php';
 	include_once 'include/head.php';
 	include_once 'include/script.php';

	if (!isset($_SESSION['id_user'])) {
	    header("Location: login.php");
	    exit;
	}
	
	if ($dataUser['jabatan'] == 'petugas') {
        header("Location: index.php");
        exit;
    }
    
	$id_user = $_GET['id_user'];

    $data_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user'"));
    $username = $data_user['username'];

    if ($username == 'admin') {
    	echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'User admin tidak dapat dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'user.php';
	                }
	            });
	        </script>
	    ";
	    exit;
    }

    $foto = $data_user['foto'];
    $image_path = 'assets/img/profiles/' . $foto;
	
	$delete_user = mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id_user'");

	if ($delete_user) {
        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'User $username berhasil dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

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
	                text: 'User " . $username . " berhasil dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'user.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	} else {
        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'User $username gagal dihapus!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

	    echo "
	        <script>
	            Swal.fire({
	                icon: 'error',
	                title: 'Gagal!',
	                text: 'User " . $username . " gagal dihapus!'
	            }).then((result) => {
	                if (result.isConfirmed) {
	                    window.location.href = 'user.php';
	                }
	            });
	        </script>
	    ";
	    exit;
	}

?>
</body>
