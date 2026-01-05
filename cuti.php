<?php
require_once 'db_connect.php';
$pegawai_list = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Cuti - GojekStaff</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="main-container">
        <div class="header-section">
            <div class="header-text">
                <h1>Daftar Pengajuan Cuti</h1>
                <p>Kelola permohonan izin, sakit, dan cuti tahunan pegawai.</p>
            </div>
            <button class="btn-primary" onclick="document.getElementById('modalCuti').style.display='flex'">
                <i class="fas fa-calendar-plus"></i> Ajukan Cuti/Izin
            </button>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Jenis Keperluan</th>
                            <th>Periode Cuti</th>
                            <th>Alasan / Keterangan</th>
                            <th>Status Pengajuan</th>
                            <th class="text-center">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT cuti_izin.*, pegawai.nama FROM cuti_izin 
                                JOIN pegawai ON cuti_izin.id_pegawai = pegawai.id_pegawai 
                                ORDER BY cuti_izin.id_cuti DESC";
                        $query = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($query)):
                            $status_lower = strtolower($row['status_pengajuan']);
                            ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar-small"><?= substr($row['nama'], 0, 1); ?></div>
                                        <span class="font-bold"><?= $row['nama']; ?></span>
                                    </div>
                                </td>
                                <td><span
                                        class="type-indicator type-<?= strtolower($row['jenis']); ?>"><?= $row['jenis']; ?></span>
                                </td>
                                <td>
                                    <div class="date-range">
                                        <div class="date-item">
                                            <small>Mulai</small>
                                            <span><?= date('d M Y', strtotime($row['tgl_mulai'])); ?></span>
                                        </div>
                                        <i class="fas fa-arrow-right"></i>
                                        <div class="date-item">
                                            <small>Selesai</small>
                                            <span><?= date('d M Y', strtotime($row['tgl_selesai'])); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="reason-text" title="<?= $row['keterangan']; ?>">
                                        <?= $row['keterangan']; ?>
                                    </p>
                                </td>
                                <td>
                                    <span class="badge-status badge-<?= $status_lower; ?>">
                                        <i
                                            class="fas <?= $status_lower == 'disetujui' ? 'fa-check' : ($status_lower == 'ditolak' ? 'fa-times' : 'fa-clock') ?>"></i>
                                        <?= $row['status_pengajuan']; ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="cuti_proses.php" method="POST" class="status-form">
                                        <input type="hidden" name="id_cuti" value="<?= $row['id_cuti']; ?>">
                                        <div class="select-wrapper">
                                            <select name="status_baru" onchange="this.form.submit()"
                                                class="status-select-modern">
                                                <option value="" disabled selected>Ubah Status</option>
                                                <option value="Pending">🕒 Set Pending</option>
                                                <option value="Disetujui">✅ Setujui</option>
                                                <option value="Ditolak">❌ Tolak</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalCuti" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Pengajuan Cuti / Izin</h3>
                <span class="close-modal"
                    onclick="document.getElementById('modalCuti').style.display='none'">&times;</span>
            </div>
            <form action="cuti_proses.php" method="POST" class="p-24">
                <div class="form-group mb-20">
                    <label>Nama Pegawai</label>
                    <select name="id_pegawai" required class="custom-select">
                        <option value="">Cari Nama Pegawai...</option>
                        <?php mysqli_data_seek($pegawai_list, 0);
                        while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>"><?= $p['nama']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group mb-20">
                    <label>Jenis Pengajuan</label>
                    <div class="type-grid">
                        <label class="type-option">
                            <input type="radio" name="jenis" value="Cuti" checked>
                            <span class="type-box">Cuti</span>
                        </label>
                        <label class="type-option">
                            <input type="radio" name="jenis" value="Izin">
                            <span class="type-box">Izin</span>
                        </label>
                        <label class="type-option">
                            <input type="radio" name="jenis" value="Sakit">
                            <span class="type-box">Sakit</span>
                        </label>
                    </div>
                </div>

                <div class="form-grid mb-20">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" required>
                    </div>
                </div>

                <div class="form-group mb-20">
                    <label>Alasan / Keterangan Lengkap</label>
                    <textarea name="keterangan" rows="4" placeholder="Jelaskan alasan pengajuan secara detail..."
                        required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('modalCuti').style.display='none'">Batal</button>
                    <button type="submit" name="simpan_cuti" class="btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>