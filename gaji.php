<?php
require_once 'db_connect.php';

$sql_pegawai = "SELECT pegawai.id_pegawai, pegawai.nama, jabatan.gaji_pokok, jabatan.tunjangan_makan 
                FROM pegawai 
                JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan";
$pegawai_list = mysqli_query($conn, $sql_pegawai);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll - GojekStaff</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="main-container">
        <div class="header-section">
            <div class="header-text">
                <h1>Sistem Penggajian</h1>
                <p>Kelola slip gaji bulanan dan riwayat pembayaran pegawai secara otomatis.</p>
            </div>
            <button class="btn-primary" onclick="document.getElementById('modalGaji').style.display='flex'">
                <i class="fas fa-money-check-alt"></i> Input Gaji Baru
            </button>
        </div>

        <div class="payroll-summary">
            <div class="summary-card">
                <i class="fas fa-wallet"></i>
                <div>
                    <small>Periode Berjalan</small>
                    <h4>
                        <?= date('F Y'); ?>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Periode</th>
                            <th class="text-right">Nominal Gaji</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center">Dokumen</th>
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
                                <td>
                                    <div class="user-info">
                                        <i class="fas fa-user-circle text-muted"></i>
                                        <span class="font-bold">
                                            <?= $row['nama']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-period">
                                        <i class="far fa-calendar-alt"></i>
                                        <?= $row['bulan'] . " " . $row['tahun']; ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="total-gaji-amount">
                                        Rp
                                        <?= number_format($row['total_gaji'], 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td><span class="text-muted">
                                        <?= date('d/m/Y', strtotime($row['tgl_bayar'])); ?>
                                    </span></td>
                                <td class="text-center">
                                    <button class="btn-print" onclick="alert('Fitur Cetak Slip dalam pengembangan')">
                                        <i class="fas fa-print"></i> Slip Gaji
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalGaji" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Pembayaran Gaji</h3>
                <span class="close-modal"
                    onclick="document.getElementById('modalGaji').style.display='none'">&times;</span>
            </div>
            <form action="gaji_proses.php" method="POST" class="p-24">
                <div class="form-group mb-20">
                    <label>Pilih Penerima Gaji</label>
                    <select name="id_pegawai" id="pilih_pegawai" onchange="updateTotalGaji()" class="custom-select"
                        required>
                        <option value="">-- Pilih Pegawai --</option>
                        <?php while ($p = mysqli_fetch_assoc($pegawai_list)): ?>
                            <option value="<?= $p['id_pegawai']; ?>" data-gapok="<?= $p['gaji_pokok']; ?>"
                                data-tunjangan="<?= $p['tunjangan_makan']; ?>">
                                <?= $p['nama']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-grid mb-20">
                    <div class="form-group">
                        <label>Bulan</label>
                        <select name="bulan" required>
                            <?php
                            $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                            foreach ($bulan as $b)
                                echo "<option value='$b' " . (date('F') == $b ? 'selected' : '') . ">$b</option>";
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" value="<?= date('Y'); ?>" required>
                    </div>
                </div>

                <div class="salary-breakdown" id="breakdown_box" style="display:none;">
                    <div class="breakdown-item">
                        <span>Gaji Pokok:</span>
                        <strong id="label_gapok">Rp 0</strong>
                    </div>
                    <div class="breakdown-item">
                        <span>Tunjangan:</span>
                        <strong id="label_tunjangan">Rp 0</strong>
                    </div>
                    <hr>
                    <div class="breakdown-total">
                        <span>Total Take Home Pay:</span>
                        <strong id="label_total">Rp 0</strong>
                    </div>
                </div>

                <input type="hidden" name="total_gaji" id="input_total_gaji">

                <div class="modal-footer">
                    <button type="button" class="btn-secondary"
                        onclick="document.getElementById('modalGaji').style.display='none'">Batal</button>
                    <button type="submit" name="bayar_gaji" class="btn-primary">Proses Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function formatRupiah(angka) {
            return "Rp " + new Intl.NumberFormat('id-ID').format(angka);
        }

        function updateTotalGaji() {
            const select = document.getElementById('pilih_pegawai');
            const selectedOption = select.options[select.selectedIndex];
            const breakdown = document.getElementById('breakdown_box');

            if (selectedOption.value !== "") {
                const gapok = parseInt(selectedOption.getAttribute('data-gapok'));
                const tunjangan = parseInt(selectedOption.getAttribute('data-tunjangan'));
                const total = gapok + tunjangan;

                document.getElementById('input_total_gaji').value = total;
                document.getElementById('label_gapok').innerText = formatRupiah(gapok);
                document.getElementById('label_tunjangan').innerText = formatRupiah(tunjangan);
                document.getElementById('label_total').innerText = formatRupiah(total);

                breakdown.style.display = 'block';
            } else {
                breakdown.style.display = 'none';
                document.getElementById('input_total_gaji').value = "";
            }
        }
    </script>
</body>

</html>