<?php
require_once 'db_connect.php';
$pegawai_list = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC");
date_default_timezone_set('Asia/Jakarta');

$filter_tgl = isset($_GET['filter_tgl']) ? $_GET['filter_tgl'] : '';

function hitungDurasi($masuk, $keluar)
{
    if (!$masuk || !$keluar)
        return "-";
    $awal = strtotime($masuk);
    $akhir = strtotime($keluar);
    $diff = $akhir - $awal;
    $jam = floor($diff / (60 * 60));
    $menit = floor(($diff - ($jam * 60 * 60)) / 60);
    return "$jam j $menit m";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Pegawai - GojekStaff</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="main-container">
        <div class="header-section">
            <div class="header-text">
                <h1>Presensi Kehadiran</h1>
                <p>Pantau jam masuk, keluar, dan durasi kerja staf secara real-time.</p>
            </div>
            <button class="btn-primary" onclick="document.getElementById('modalAbsen').style.display='flex'">
                <i class="fas fa-fingerprint"></i> Input Kehadiran
            </button>
        </div>

        <div class="filter-card">
            <form action="" method="GET" class="filter-form">
                <div class="input-with-icon">
                    <i class="fas fa-calendar-day"></i>
                    <input type="date" name="filter_tgl" value="<?= $filter_tgl; ?>">
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Cari Data
                </button>
                <?php if ($filter_tgl != ''): ?>
                    <a href="absensi.php" class="btn-reset">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pegawai</th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Jam Keluar</th>
                            <th>Status</th>
                            <th>Durasi Kerja</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = ($filter_tgl != '')
                            ? "SELECT absensi.*, pegawai.nama FROM absensi JOIN pegawai ON absensi.id_pegawai = pegawai.id_pegawai WHERE absensi.tanggal = '$filter_tgl' ORDER BY absensi.id_absensi DESC"
                            : "SELECT absensi.*, pegawai.nama FROM absensi JOIN pegawai ON absensi.id_pegawai = pegawai.id_pegawai ORDER BY absensi.tanggal DESC, absensi.id_absensi DESC";

                        $query = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($query) > 0):
                            while ($row = mysqli_fetch_assoc($query)):
                                $status_class = strtolower($row['status'] == 'Izin/Cuti' ? 'izin' : $row['status']);
                                ?>
                                <tr>
                                    <td class="font-medium">
                                        <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                    </td>
                                    <td class="font-bold">
                                        <?= $row['nama']; ?>
                                    </td>
                                    <td class="text-center time-cell <?= !$row['jam_masuk'] ? 'empty' : '' ?>">
                                        <?= $row['jam_masuk'] ?: '--:--'; ?>
                                    </td>
                                    <td class="text-center time-cell <?= !$row['jam_keluar'] ? 'empty' : '' ?>">
                                        <?= $row['jam_keluar'] ?: '--:--'; ?>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-<?= $status_class; ?>">
                                            <?= $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="duration-box">
                                            <i class="far fa-clock"></i>
                                            <?= ($row['status'] == 'Hadir') ? hitungDurasi($row['jam_masuk'], $row['jam_keluar']) : '-'; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'Hadir' && empty($row['jam_keluar'])): ?>
                                            <a href="absensi_proses.php?absen_keluar=<?= $row['id_absensi']; ?>"
                                                class="btn-checkout">
                                                Absen Keluar
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-check-double"></i> Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Tidak ada data kehadiran ditemukan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalAbsen" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Input Kehadiran Baru</h3>
                <span class="close-modal"
                    onclick="document.getElementById('modalAbsen').style.display='none'">&times;</span>
            </div>
            <form action="absensi_proses.php" method="POST" class="p-24">
                <div class="form-group mb-20">
                    <label>Nama Pegawai</label>
                    <select name="id_pegawai" class="custom-select" required>
                        <option value="">Cari Nama Pegawai...</option>
                        <?php mysqli_data_seek($pegawai_list, 0);
                        while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>">
                                <?= $p['nama']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group mb-20">
                    <label>Status Kehadiran</label>
                    <div class="status-options">
                        <label class="status-radio">
                            <input type="radio" name="status" value="Hadir" checked>
                            <span class="radio-card"><i class="fas fa-check-circle"></i> Hadir</span>
                        </label>
                        <label class="status-radio">
                            <input type="radio" name="status" value="Alpa">
                            <span class="radio-card"><i class="fas fa-times-circle"></i> Alpa</span>
                        </label>
                        <label class="status-radio">
                            <input type="radio" name="status" value="Izin/Cuti">
                            <span class="radio-card"><i class="fas fa-envelope"></i> Izin</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('modalAbsen').style.display='none'">Batal</button>
                    <button type="submit" name="simpan_masuk" class="btn-primary">Konfirmasi Kehadiran</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>