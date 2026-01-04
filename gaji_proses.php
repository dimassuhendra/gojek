<?php
require_once 'db_connect.php';

if (isset($_POST['bayar_gaji'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $total_gaji = $_POST['total_gaji'];
    $tgl_bayar = date('Y-m-d');

    // Cek apakah bulan ini untuk pegawai tersebut sudah dibayar agar tidak double
    $cek = mysqli_query($conn, "SELECT * FROM gaji WHERE id_pegawai = '$id_pegawai' AND bulan = '$bulan' AND tahun = '$tahun'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Gaji pegawai ini untuk bulan $bulan $tahun sudah pernah diinput!'); window.location='gaji.php';</script>";
    } else {
        $query = "INSERT INTO gaji (id_pegawai, bulan, tahun, total_gaji, tgl_bayar) 
                  VALUES ('$id_pegawai', '$bulan', '$tahun', '$total_gaji', '$tgl_bayar')";

        if (mysqli_query($conn, $query)) {
            header("Location: gaji.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>