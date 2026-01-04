<?php require_once 'db_connect.php';
$sql_pegawai = mysqli_query($conn, "SELECT count(*) as total FROM pegawai");
$data_pegawai = mysqli_fetch_assoc($sql_pegawai);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard GojekStaff</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <img src="assets/logo.png" alt="Home Logo" class="home-logo">

        <div class="hero">
            <h1>Selamat Datang di GojekStaff</h1>
            <p>Sistem Manajemen Kepegawaian Internal Terpadu</p>

            <div class="card-grid">
                <div class="card">
                    <h3>Total Pegawai</h3>
                    <p style="font-size: 2em; color: #059212; font-weight: bold;">
                        <?php echo $data_pegawai['total']; ?>
                    </p>
                </div>
                <div class="card">
                    <h3>Cuti Pending</h3>
                    <p style="font-size: 2em; color: #059212; font-weight: bold;">3</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>