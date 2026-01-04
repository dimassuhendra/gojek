<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_kepegawaian";

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>