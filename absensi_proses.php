<?php
require_once 'db_connect.php';
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['simpan_masuk'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $tanggal = date('Y-m-d');
    $status = $_POST['status'];
    $jam_sekarang = date('H:i:s');

    if ($status == 'Hadir') {
        // Jika Hadir, catat jam masuk sekarang
        $query = "INSERT INTO absensi (id_pegawai, tanggal, jam_masuk, status) 
                  VALUES ('$id_pegawai', '$tanggal', '$jam_sekarang', '$status')";
    } else {
        // Jika Alpa atau Izin, jam masuk dan keluar dibiarkan NULL (kosong)
        $query = "INSERT INTO absensi (id_pegawai, tanggal, jam_masuk, jam_keluar, status) 
                  VALUES ('$id_pegawai', '$tanggal', NULL, NULL, '$status')";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: absensi.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['absen_keluar'])) {
    $id_absensi = $_GET['absen_keluar'];
    $jam_keluar = date('H:i:s');

    $query = "UPDATE absensi SET jam_keluar = '$jam_keluar' WHERE id_absensi = '$id_absensi'";

    if (mysqli_query($conn, $query)) {
        header("Location: absensi.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>