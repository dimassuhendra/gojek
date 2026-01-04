<?php
require_once 'db_connect.php';
$pegawai_list = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC");
date_default_timezone_set('Asia/Jakarta');

// Ambil tanggal dari filter jika ada, jika tidak kosongkan
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

    return "$jam Jam $menit Menit";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Pegawai - GojekStaff</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="content-pegawai">
        <h2>Presensi Kehadiran Pegawai</h2>

        <div class="filter-box" style="justify-content: space-between;">
            <div>
                <button class="btn-tambah" onclick="document.getElementById('modalAbsen').style.display='block'"
                    style="margin-bottom:0">
                    + Input Kehadiran Baru
                </button>
            </div>

            <form action="" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <label>Filter Tanggal:</label>
                <input type="date" name="filter_tgl" value="<?= $filter_tgl; ?>"
                    style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                <button type="submit" class="btn-edit"
                    style="background-color: #059212; color: white; padding: 10px 15px;">Cari</button>
                <?php if ($filter_tgl != ''): ?>
                    <a href="absensi.php" class="btn-hapus" style="text-decoration: none; padding: 10px 15px;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pegawai</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Status</th>
                    <th>Lama Bekerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Logika SQL Filter
                if ($filter_tgl != '') {
                    $sql = "SELECT absensi.*, pegawai.nama FROM absensi 
                            JOIN pegawai ON absensi.id_pegawai = pegawai.id_pegawai 
                            WHERE absensi.tanggal = '$filter_tgl'
                            ORDER BY absensi.id_absensi DESC";
                } else {
                    $sql = "SELECT absensi.*, pegawai.nama FROM absensi 
                            JOIN pegawai ON absensi.id_pegawai = pegawai.id_pegawai 
                            ORDER BY absensi.tanggal DESC, absensi.id_absensi DESC";
                }

                $query = mysqli_query($conn, $sql);

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td style="<?= $row['status'] != 'Hadir' ? 'color: red; font-style: italic;' : ''; ?>">
                                <?= $row['jam_masuk'] ?: 'Kosong'; ?>
                            </td>
                            <td style="<?= $row['status'] != 'Hadir' ? 'color: red; font-style: italic;' : ''; ?>">
                                <?= $row['jam_keluar'] ?: 'Kosong'; ?>
                            </td>
                            <td>
                                <span
                                    class="badge badge-<?= strtolower($row['status'] == 'Izin/Cuti' ? 'izin' : $row['status']); ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= ($row['status'] == 'Hadir') ? hitungDurasi($row['jam_masuk'], $row['jam_keluar']) : '-'; ?></strong>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Hadir' && empty($row['jam_keluar'])): ?>
                                    <a href="absensi_proses.php?absen_keluar=<?= $row['id_absensi']; ?>" class="btn-edit"
                                        style="background-color: #06D001; color:white;">
                                        Klik Jam Keluar
                                    </a>
                                <?php else: ?>
                                    <small>-</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                } else {
                    echo "<tr><td colspan='7' style='padding: 20px;'>Tidak ada data absensi untuk tanggal ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="modalAbsen" class="modal">
        <div class="modal-content">
            <h3>Input Kehadiran</h3>
            <form action="absensi_proses.php" method="POST">
                <div class="form-group">
                    <label>Pilih Pegawai</label>
                    <select name="id_pegawai" required>
                        <option value="">-- Pilih Pegawai --</option>
                        <?php while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>"><?= $p['nama']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Kehadiran</label>
                    <select name="status">
                        <option value="Hadir">Hadir (Jam otomatis)</option>
                        <option value="Alpa">Alpa</option>
                        <option value="Izin/Cuti">Izin/Cuti</option>
                    </select>
                </div>
                <button type="submit" name="simpan_masuk" class="btn-tambah" style="width:100%">Simpan Data</button>
                <button type="button" onclick="document.getElementById('modalAbsen').style.display='none'"
                    style="width:100%; background:#ccc; border:none; padding:10px; border-radius:5px; margin-top:10px; cursor:pointer;">Batal</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>