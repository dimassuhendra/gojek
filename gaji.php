<?php
require_once 'db_connect.php';

// Ambil data pegawai beserta informasi jabatannya untuk perhitungan di modal
$sql_pegawai = "SELECT pegawai.id_pegawai, pegawai.nama, jabatan.gaji_pokok, jabatan.tunjangan_makan 
                FROM pegawai 
                JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan";
$pegawai_list = mysqli_query($conn, $sql_pegawai);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penggajian Pegawai - GojekStaff</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="content-pegawai">
        <h2>Histori Penggajian Pegawai</h2>

        <div class="filter-box">
            <button class="btn-tambah" onclick="document.getElementById('modalGaji').style.display='block'">
                + Input Pembayaran Gaji
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Bulan / Tahun</th>
                    <th>Total Gaji (Rp)</th>
                    <th>Tanggal Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT gaji.*, pegawai.nama FROM gaji 
                        JOIN pegawai ON gaji.id_pegawai = pegawai.id_pegawai 
                        ORDER BY gaji.id_gaji DESC";
                $query = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><strong>
                                <?= $row['nama']; ?>
                            </strong></td>
                        <td>
                            <?= $row['bulan'] . " " . $row['tahun']; ?>
                        </td>
                        <td class="total-gaji">Rp
                            <?= number_format($row['total_gaji'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($row['tgl_bayar'])); ?>
                        </td>
                        <td>
                            <button class="btn-edit" onclick="alert('Fitur Cetak Slip dalam pengembangan')">Cetak
                                Slip</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="modalGaji" class="modal">
        <div class="modal-content">
            <h3>Input Pembayaran Gaji</h3>
            <form action="gaji_proses.php" method="POST">
                <div class="form-group">
                    <label>Pilih Pegawai</label>
                    <select name="id_pegawai" id="pilih_pegawai" onchange="updateTotalGaji()" required>
                        <option value="">-- Pilih Pegawai --</option>
                        <?php while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>" data-gapok="<?= $p['gaji_pokok']; ?>"
                                data-tunjangan="<?= $p['tunjangan_makan']; ?>">
                                <?= $p['nama']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Bulan</label>
                        <select name="bulan" required>
                            <?php
                            $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                            foreach ($bulan as $b)
                                echo "<option value='$b'>$b</option>";
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Tahun</label>
                        <input type="number" name="tahun" value="<?= date('Y'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Total Gaji (Otomatis dari Jabatan)</label>
                    <input type="number" name="total_gaji" id="input_total_gaji" readonly style="background: #eee;">
                </div>
                <button type="submit" name="bayar_gaji" class="btn-tambah" style="width:100%">Simpan Pembayaran</button>
                <button type="button" onclick="document.getElementById('modalGaji').style.display='none'"
                    style="width:100%; background:#ccc; border:none; padding:10px; border-radius:5px; margin-top:10px; cursor:pointer;">Batal</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function updateTotalGaji() {
            const select = document.getElementById('pilih_pegawai');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value !== "") {
                const gapok = parseInt(selectedOption.getAttribute('data-gapok'));
                const tunjangan = parseInt(selectedOption.getAttribute('data-tunjangan'));
                document.getElementById('input_total_gaji').value = gapok + tunjangan;
            } else {
                document.getElementById('input_total_gaji').value = "";
            }
        }
    </script>
</body>

</html>