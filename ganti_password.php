<?php 
    require_once 'connection.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <title>Ganti Password - <?= $dataUser['username']; ?></title>
    <?php include_once 'include/head.php'; ?>
</head> <!--end::Head--> <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <?php 
        if (isset($_POST['btnGantiPassword'])) {
            $password_lama = $_POST['password_lama'];
            $password_baru = $_POST['password_baru'];
            $ulangi_password_baru = $_POST['ulangi_password_baru'];
        
            if (password_verify($password_lama, $dataUser['password'])) {
                if ($password_baru == $ulangi_password_baru) {
                    $password_baru_hash = password_hash($password_baru, PASSWORD_DEFAULT);
                    $update_password = mysqli_query($conn, "UPDATE user SET password = '$password_baru_hash' WHERE id_user = '$id_user'");
                    if ($update_password) {
                        $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Password berhasil diperbaharui!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                        echo "
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Password berhasil diperbaharui!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'profile.php';
                                    }
                                });
                            </script>
                        ";
                        exit;
                    } else {
                        $log_gagal = mysqli_query($conn, "INSERT INTO log VALUES ('', 'Password gagal diperbaharui!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

                        echo "
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Password gagal diperbaharui!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'ganti_password.php';
                                    }
                                });
                            </script>
                        ";
                        exit;
                    }
                } 
                else 
                {
                    echo "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Password baru tidak sama dengan ulangi password baru!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'ganti_password.php';
                                }
                            });
                        </script>
                    ";
                    exit;
                }
            } 
            else 
            {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Password lama salah!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'ganti_password.php';
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
                            <h3 class="mb-0">Ubah Profile - <?= $dataUser['username']; ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Ubah Profile
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-primary card-outline mb-4">
                                <form method="post"> 
                                    <div class="card-body">
                                        <div class="mb-3"> 
                                            <label for="password_lama" class="form-label">Password Lama</label> 
                                            <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="password_baru" class="form-label">Password Baru</label> 
                                            <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                                        </div>
                                        <div class="mb-3"> 
                                            <label for="ulangi_password_baru" class="form-label">Ulangi Password Baru</label> 
                                            <input type="password" class="form-control" id="ulangi_password_baru" name="ulangi_password_baru" required>
                                        </div>
                                    </div> 
                                    <div class="card-footer pt-3 text-end">
                                        <button type="submit" name="btnGantiPassword" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Submit</button>
                                    </div>
                                </form> <!--end::Form-->
                            </div>
                        </div>
                    </div>
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> 
        <?php include_once 'include/footer.php'; ?>
    </div> <!--end::App Wrapper--> 
    <?php include_once 'include/script.php'; ?>
    
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview-img');
                var outputCircle = document.getElementById('preview-img-circle');
                output.src = reader.result;
                outputCircle.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body><!--end::Body-->

</html>