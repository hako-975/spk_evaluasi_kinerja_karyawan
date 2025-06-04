<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }

    $id_karyawan = $_GET['id_karyawan'];
    
    $data_karyawan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'"));
    
    if ($data_karyawan == null) {
        header("Location: karyawan.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ubah Karyawan - <?= $data_karyawan['nama_karyawan']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnUbahKaryawan'])) {
            $nik = htmlspecialchars($_POST['nik']);
            $nama_karyawan = htmlspecialchars($_POST['nama_karyawan']);
            $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
            $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
            $no_hp = htmlspecialchars($_POST['no_hp']);
            $alamat = htmlspecialchars($_POST['alamat']);

            $foto = $data_karyawan['foto'];
            $foto_new = $_FILES['foto']['name'];
            if ($foto_new != '') {
                $acc_extension = array('png', 'jpg', 'jpeg', 'gif');
                $extension = explode('.', $foto_new);
                $extension_lower = strtolower(end($extension));
                $size = $_FILES['foto']['size'];
                $file_tmp = $_FILES['foto']['tmp_name'];     

                if ($size > 5253120) {
                    echo "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Ukuran file terlalu besar!',
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

                if(!in_array($extension_lower, $acc_extension))
                {
                    echo "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'File yang di upload bukan gambar!',
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

                $image_path = 'assets/img/profiles/' . $foto;
                
                if ($foto != 'default.jpg' && $foto != '') {
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                $foto = uniqid() . '_' . time() . '_' . $foto_new;
            }
            
            $update_karyawan = mysqli_query($conn, "UPDATE karyawan SET nik = '$nik', nama_karyawan = '$nama_karyawan', tanggal_lahir = '$tanggal_lahir', jenis_kelamin = '$jenis_kelamin', no_hp = '$no_hp', alamat = '$alamat', foto = '$foto' WHERE id_karyawan = '$id_karyawan'");

            if ($update_karyawan) {
                $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Karyawan $nama_karyawan berhasil diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                if ($foto_new != '') {
                    $file_tmp = $_FILES['foto']['tmp_name'];     
                    move_uploaded_file($file_tmp, 'assets/img/profiles/' . $foto);
                }

                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Karyawan " . $nama_karyawan . " berhasil diubah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'karyawan.php';
                            }
                        });
                    </script>
                ";
                exit;
            } else {
                $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Karyawan $nama_karyawan gagal diubah!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Karyawan " . $nama_karyawan . " gagal diubah!'
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
                        <div class="col-sm-6">
                            <h3 class="mb-0">Ubah Karyawan - <?= $data_karyawan['nama_karyawan']; ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="karyawan.php">Karyawan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Ubah Karyawan
                                </li>
                            </ol>
                        </div>
                    </div> <!--end::Row-->
                </div> <!--end::Container-->
            </div>
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!-- Info boxes -->
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-danger card-outline mb-4">
                                <form method="post" enctype="multipart/form-data"> 
                                    <div class="card-body">
                                        <div class="mb-3"> 
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="number" class="form-control" id="nik" name="nik" value="<?= $data_karyawan['nik']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?= $data_karyawan['nama_karyawan']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir Karyawan</label>
                                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $data_karyawan['tanggal_lahir']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin Karyawan</label> 
                                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                                <?php if ($data_karyawan['jenis_kelamin'] == 'laki-laki'): ?>
                                                    <option value="laki-laki"><?= ucwords('laki-laki'); ?></option>
                                                    <option value="perempuan"><?= ucwords('perempuan'); ?></option>
                                                <?php else: ?>
                                                    <option value="perempuan"><?= ucwords('perempuan'); ?></option>
                                                    <option value="laki-laki"><?= ucwords('laki-laki'); ?></option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="no_hp" class="form-label">No. Telepon</label> 
                                            <input type="number" class="form-control" id="no_hp" name="no_hp" value="<?= $data_karyawan['no_hp']; ?>" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="alamat" name="alamat" required><?= $data_karyawan['alamat']; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Foto</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="foto" name="foto" onchange="previewImage(event)"> 
                                                <label class="input-group-text" for="foto">Upload</label> 
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnUbahKaryawan" class="btn btn-danger"><i class="fas fa-fw fa-save"></i> Submit</button>
                                    </div> 
                                </form> <!--end::Form-->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-danger card-outline mb-4">
                                <div class="card-body text-center">
                                    <h5 class="form-label">Preview Foto</h5>
                                    <div class="row justify-content-between">
                                        <div class="col">
                                            <img id="preview-img" class="img-fluid rounded-3" src="assets/img/profiles/<?= $data_karyawan['foto']; ?>" alt="<?= $data_karyawan['foto']; ?>">
                                        </div>
                                        <div class="col">
                                            <img id="preview-img-circle" class="img-fluid rounded-circle" src="assets/img/profiles/<?= $data_karyawan['foto']; ?>" alt="<?= $data_karyawan['foto']; ?>">
                                        </div>
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