<?php
require_once 'db_connect.php';

// Ambil Total Pegawai
$sql_pegawai = mysqli_query($conn, "SELECT count(*) as total FROM pegawai");
$data_pegawai = mysqli_fetch_assoc($sql_pegawai);

// Ambil Total Cuti Pending (Real-time)
$sql_cuti = mysqli_query($conn, "SELECT count(*) as total FROM cuti_izin WHERE status_pengajuan = 'Pending'");
$data_cuti = mysqli_fetch_assoc($sql_cuti);

// Ambil Total Kehadiran Hari Ini
$tgl_sekarang = date('Y-m-d');
$sql_absen = mysqli_query($conn, "SELECT count(*) as total FROM absensi WHERE tanggal = '$tgl_sekarang' AND status = 'Hadir'");
$data_absen = mysqli_fetch_assoc($sql_absen);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GojekStaff</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <main class="dashboard-container">
        <section class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat Datang, Admin 👋</h1>
                <p>Hari ini adalah <strong>
                        <?= date('l, d F Y'); ?>
                    </strong>. Berikut adalah ringkasan operasional kantor Anda.</p>
            </div>
            <div class="welcome-image">
                <img src="assets/logo.png" alt="GojekStaff Logo">
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>Total Pegawai</h3>
                    <p class="stat-number">
                        <?= $data_pegawai['total']; ?>
                    </p>
                    <span class="stat-label">Orang terdaftar</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-details">
                    <h3>Cuti Pending</h3>
                    <p class="stat-number">
                        <?= $data_cuti['total']; ?>
                    </p>
                    <span class="stat-label">Perlu tinjauan</span>
                </div>
                <a href="cuti.php" class="stat-link">Lihat Detail <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>Hadir Hari Ini</h3>
                    <p class="stat-number">
                        <?= $data_absen['total']; ?>
                    </p>
                    <span class="stat-label">Sudah absen masuk</span>
                </div>
            </div>
        </div>

        <section class="quick-actions">
            <h2>Akses Cepat</h2>
            <div class="action-buttons">
                <a href="pegawai.php" class="action-item">
                    <i class="fas fa-user-plus"></i>
                    <span>Kelola Pegawai</span>
                </a>
                <a href="absensi.php" class="action-item">
                    <i class="fas fa-fingerprint"></i>
                    <span>Input Absensi</span>
                </a>
                <a href="gaji.php" class="action-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Payroll</span>
                </a>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

</body>

</html>