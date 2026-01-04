<?php
require_once 'db_connect.php';

// 1. Logika Tambah Pengajuan Baru
if (isset($_POST['simpan_cuti'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $jenis = $_POST['jenis'];
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO cuti_izin (id_pegawai, tgl_mulai, tgl_selesai, jenis, keterangan, status_pengajuan) 
              VALUES ('$id_pegawai', '$tgl_mulai', '$tgl_selesai', '$jenis', '$keterangan', 'Pending')";

    if (mysqli_query($conn, $query)) {
        header("Location: cuti.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// 2. Logika Update Status (Pending/Disetujui/Ditolak)
if (isset($_POST['update_status'])) {
    $id_cuti = $_POST['id_cuti'];
    $status_baru = $_POST['status_baru'];

    if ($status_baru != "") {
        $query = "UPDATE cuti_izin SET status_pengajuan = '$status_baru' WHERE id_cuti = '$id_cuti'";
        mysqli_query($conn, $query);
    }
    header("Location: cuti.php");
}
?>