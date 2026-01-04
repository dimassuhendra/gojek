<?php
require_once 'db_connect.php';

if (isset($_POST['simpan'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $id_jabatan = $_POST['id_jabatan'];
    $telepon = $_POST['telepon'];

    if ($id_pegawai == "") {
        // Mode TAMBAH
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO pegawai (nip, nama, id_jabatan, telepon, password) 
                  VALUES ('$nip', '$nama', '$id_jabatan', '$telepon', '$password')";
    } else {
        // Mode EDIT
        $query = "UPDATE pegawai SET 
                  nip = '$nip', 
                  nama = '$nama', 
                  id_jabatan = '$id_jabatan', 
                  telepon = '$telepon' 
                  WHERE id_pegawai = '$id_pegawai'";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: pegawai.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>