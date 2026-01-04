<?php
require_once 'db_connect.php';
$pegawai_list = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Cuti - GojekStaff</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="content-pegawai">
        <h2>Daftar Pengajuan Cuti & Izin</h2>

        <div class="filter-box">
            <button class="btn-tambah" onclick="document.getElementById('modalCuti').style.display='block'">
                + Ajukan Cuti/Izin Baru
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Jenis</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT cuti_izin.*, pegawai.nama FROM cuti_izin 
                        JOIN pegawai ON cuti_izin.id_pegawai = pegawai.id_pegawai 
                        ORDER BY cuti_izin.id_cuti DESC";
                $query = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><strong>
                                <?= $row['nama']; ?>
                            </strong></td>
                        <td>
                            <?= $row['jenis']; ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($row['tgl_mulai'])); ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($row['tgl_selesai'])); ?>
                        </td>
                        <td>
                            <?= $row['keterangan']; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= strtolower($row['status_pengajuan']); ?>">
                                <?= $row['status_pengajuan']; ?>
                            </span>
                        </td>
                        <td>
                            <form action="cuti_proses.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_cuti" value="<?= $row['id_cuti']; ?>">
                                <select name="status_baru" onchange="this.form.submit()" class="status-select">
                                    <option value="">Ganti Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="modalCuti" class="modal">
        <div class="modal-content">
            <h3>Form Pengajuan Cuti</h3>
            <form action="cuti_proses.php" method="POST">
                <div class="form-group">
                    <label>Pilih Pegawai</label>
                    <select name="id_pegawai" required>
                        <option value="">-- Pilih Pegawai --</option>
                        <?php mysqli_data_seek($pegawai_list, 0);
                        while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>">
                                <?= $p['nama']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="Cuti">Cuti</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Keterangan / Alasan</label>
                    <textarea name="keterangan"
                        style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;" rows="3"
                        required></textarea>
                </div>
                <button type="submit" name="simpan_cuti" class="btn-tambah" style="width:100%">Kirim Pengajuan</button>
                <button type="button" onclick="document.getElementById('modalCuti').style.display='none'"
                    style="width:100%; background:#ccc; border:none; padding:10px; border-radius:5px; margin-top:10px; cursor:pointer;">Batal</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>