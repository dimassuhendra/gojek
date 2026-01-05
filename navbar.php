<nav class="navbar">
    <div class="navbar-container">
        <a href="index.php" class="navbar-brand">
            <i class="fas fa-motorcycle"></i> Gojek<span>Staff</span>
        </a>

        <ul class="navbar-menu">
            <li><a href="index.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Beranda</a></li>
            <li><a href="pegawai.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'pegawai.php' ? 'active' : '' ?>">Pegawai</a></li>
            <li><a href="absensi.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'absensi.php' ? 'active' : '' ?>">Absensi</a></li>
            <li><a href="cuti.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cuti.php' ? 'active' : '' ?>">Cuti</a>
            </li>
            <li><a href="gaji.php" class="<?= basename($_SERVER['PHP_SELF']) == 'gaji.php' ? 'active' : '' ?>">Gaji</a>
            </li>
        </ul>

        <div class="navbar-user">
            <i class="far fa-user-circle"></i> Admin
        </div>
    </div>
</nav>