<?php 
    require_once 'connection.php';

    $username = $dataUser['username'];
    $log_berhasil = mysqli_query($conn, "INSERT INTO log VALUES ('', 'User $username berhasil logout!', CURRENT_TIMESTAMP(), " . $dataUser['id_user'] . ")");

    session_destroy();
    header("Location: login.php");
    exit;
?>