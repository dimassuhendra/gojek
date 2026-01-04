<?php
require_once 'db_connect.php';

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai = '$id'");
    header("Location: pegawai.php");
}

// Ambil data jabatan untuk dropdown di Modal
$jabatans = mysqli_query($conn, "SELECT * FROM jabatan");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pegawai - GojekStaff</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="content-pegawai">
        <h2>Data Pegawai Gojek</h2>
        <button class="btn-tambah" onclick="openModal('tambah')">+ Tambah Pegawai</button>

        <table>
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
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
                            <?= $row['nip']; ?>
                        </td>
                        <td>
                            <?= $row['nama']; ?>
                        </td>
                        <td>
                            <?= $row['nama_jabatan']; ?>
                        </td>
                        <td>
                            <?= $row['telepon']; ?>
                        </td>
                        <td>
                            <button class="btn-edit"
                                onclick="openEditModal(<?= htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="pegawai.php?hapus=<?= $row['id_pegawai']; ?>" class="btn-hapus"
                                onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="modalPegawai" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Tambah Pegawai</h3>
            <form action="pegawai_proses.php" method="POST">
                <input type="hidden" name="id_pegawai" id="id_pegawai">
                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" id="nip" required>
                </div>
                <div class="form-group">
                    <label>Nama Pegawai</label>
                    <input type="text" name="nama" id="nama" required>
                </div>
                <div class="form-group">
                    <label>Jabatan</label>
                    <select name="id_jabatan" id="id_jabatan">
                        <?php mysqli_data_seek($jabatans, 0);
                        while ($j = mysqli_fetch_assoc($jabatans)): ?>
                            <option value="<?= $j['id_jabatan']; ?>">
                                <?= $j['nama_jabatan']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" id="telepon">
                </div>
                <div class="form-group" id="pass_group">
                    <label>Password</label>
                    <input type="password" name="password" id="password">
                </div>
                <button type="submit" name="simpan" class="btn-tambah" style="width:100%">Simpan Data</button>
                <button type="button" onclick="closeModal()"
                    style="width:100%; background:#ccc; border:none; padding:10px; border-radius:5px; cursor:pointer;">Batal</button>
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
            modal.style.display = "block";
        }

        function openEditModal(data) {
            document.getElementById('modalTitle').innerText = "Edit Pegawai";
            document.getElementById('id_pegawai').value = data.id_pegawai;
            document.getElementById('nip').value = data.nip;
            document.getElementById('nama').value = data.nama;
            document.getElementById('id_jabatan').value = data.id_jabatan;
            document.getElementById('telepon').value = data.telepon;
            document.getElementById('pass_group').style.display = "none"; // Sembunyikan pass saat edit
            modal.style.display = "block";
        }

        function closeModal() { modal.style.display = "none"; }
    </script>
</body>

</html>