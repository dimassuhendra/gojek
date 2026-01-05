<?php
require_once 'db_connect.php';

// Proses Hapus Data (Tetap sama)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai = '$id'");
    header("Location: pegawai.php");
}

$jabatans = mysqli_query($conn, "SELECT * FROM jabatan");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pegawai - GojekStaff</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="main-container">
        <div class="header-section">
            <div class="header-text">
                <h1>Data Pegawai</h1>
                <p>Manajemen informasi staf dan distribusi jabatan Gojek.</p>
            </div>
            <button class="btn-primary" onclick="openModal('tambah')">
                <i class="fas fa-plus"></i> Tambah Pegawai
            </button>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Identitas Pegawai</th>
                            <th>Jabatan</th>
                            <th>Kontak</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT pegawai.*, jabatan.nama_jabatan FROM pegawai 
                                LEFT JOIN jabatan ON pegawai.id_jabatan = jabatan.id_jabatan";
                        $query = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar"><?= substr($row['nama'], 0, 1); ?></div>
                                        <div class="details">
                                            <span class="name"><?= $row['nama']; ?></span>
                                            <span class="nip">NIP: <?= $row['nip']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge"><?= $row['nama_jabatan']; ?></span></td>
                                <td>
                                    <div class="contact-link">
                                        <i class="fas fa-phone-alt"></i> <?= $row['telepon']; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn-icon btn-edit"
                                        onclick="openEditModal(<?= htmlspecialchars(json_encode($row)); ?>)" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="pegawai.php?hapus=<?= $row['id_pegawai']; ?>" class="btn-icon btn-delete"
                                        onclick="return confirm('Yakin hapus?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalPegawai" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Pegawai</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <form action="pegawai_proses.php" method="POST">
                <input type="hidden" name="id_pegawai" id="id_pegawai">
                <div class="form-grid">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" id="nip" placeholder="Contoh: 199201..." required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" placeholder="Nama lengkap pegawai" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan">
                            <?php mysqli_data_seek($jabatans, 0);
                            while ($j = mysqli_fetch_assoc($jabatans)): ?>
                                <option value="<?= $j['id_jabatan']; ?>"><?= $j['nama_jabatan']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" id="telepon" placeholder="0812...">
                    </div>
                    <div class="form-group full-width" id="pass_group">
                        <label>Password Akun</label>
                        <input type="password" name="password" id="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" name="simpan" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        const modal = document.getElementById('modalPegawai');
        function openModal(mode) {
            document.getElementById('modalTitle').innerText = "Tambah Pegawai";
            document.getElementById('id_pegawai').value = "";
            document.getElementById('nip').value = "";
            document.getElementById('nama').value = "";
            document.getElementById('telepon').value = "";
            document.getElementById('pass_group').style.display = "block";
            modal.style.display = "flex"; // Gunakan flex agar centering sempurna
        }
        function openEditModal(data) {
            document.getElementById('modalTitle').innerText = "Edit Pegawai";
            document.getElementById('id_pegawai').value = data.id_pegawai;
            document.getElementById('nip').value = data.nip;
            document.getElementById('nama').value = data.nama;
            document.getElementById('id_jabatan').value = data.id_jabatan;
            document.getElementById('telepon').value = data.telepon;
            document.getElementById('pass_group').style.display = "none";
            modal.style.display = "flex";
        }
        function closeModal() { modal.style.display = "none"; }
        window.onclick = function (event) { if (event.target == modal) closeModal(); }
    </script>
</body>

</html>