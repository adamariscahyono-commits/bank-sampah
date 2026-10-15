<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$id_user = $_SESSION['id_user'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE id_user='$id_user'"
);

$user = mysqli_fetch_assoc($query);

$saldo = $user['saldo'];


$query_sampah = mysqli_query(
    $koneksi,
    "SELECT SUM(berat_kg) AS total_berat
     FROM transaksi
     WHERE id_user='$id_user' AND tipe='setor'"
);

$data_sampah = mysqli_fetch_assoc($query_sampah);

$total_berat = $data_sampah['total_berat'];

if ($total_berat == null) {
    $total_berat = 0;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE id_user='$id_user'"
);

$user = mysqli_fetch_assoc($query);

$saldo = $user['saldo'];


// Ambil transaksi terbaru
$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT transaksi.*, sampah.jenis_sampah
     FROM transaksi
     JOIN sampah ON transaksi.id_sampah = sampah.id_sampah
     WHERE transaksi.id_user='$id_user'
     ORDER BY transaksi.id_transaksi DESC
     LIMIT 5"
);

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Bank Sampah</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f5f8f6;
        }

        .navbar {
            background-color: white;
        }

        .brand {
            color: #198754;
            font-weight: bold;
        }

        .welcome {
            font-size: 28px;
            font-weight: bold;
        }

        .card-stat {
            border: none;
            border-radius: 16px;
        }

        .stat-icon {
            font-size: 30px;
        }

        .menu-card {
            border: none;
            border-radius: 16px;
        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<nav class="navbar shadow-sm">

    <div class="container">

        <a class="navbar-brand brand" href="#">
            ♻ Bank Sampah
        </a>

        <div>

            <span class="me-3">
                <?php echo $_SESSION['nama']; ?>
            </span>

            <a
                href="logout.php"
                class="btn btn-outline-danger btn-sm"
            >
                Logout
            </a>

        </div>

    </div>

</nav>


<!-- CONTENT -->

<div class="container py-5">


    <!-- WELCOME -->

    <div class="mb-4">

        <div class="welcome">
            Halo, <?php echo $_SESSION['nama']; ?> 👋
        </div>

        <p class="text-muted">
            Selamat datang kembali di Bank Sampah.
        </p>

    </div>


    <!-- STATISTIK -->

    <div class="row g-3">


        <!-- SALDO -->

        <div class="col-md-6">

            <div class="card card-stat shadow-sm">

                <div class="card-body p-4">

                    <div class="stat-icon">
                        💰
                    </div>

                    <p class="text-muted mb-1 mt-3">
                        Saldo Saya
                    </p>

                    <h3 class="fw-bold">
                        Rp <?php echo
                        number_format($saldo, 0, ',', '.'); ?>
                    </h3>

                </div>

            </div>

        </div>


        <!-- SAMPAH -->

        <div class="col-md-6">

            <div class="card card-stat shadow-sm">

                <div class="card-body p-4">

                    <div class="stat-icon">
                        🗑️
                    </div>

                    <p class="text-muted mb-1 mt-3">
                        Sampah Terkumpul
                    </p>

                    <h3 class="fw-bold">
                        <?php echo $total_berat; ?> Kg
                    </h3>

                </div>

            </div>

        </div>


    </div>


    <!-- MENU -->

    <div class="row g-3 mt-2">


        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body p-4">

                    <h5>
                        🗑️ Setor Sampah
                    </h5>

                    <p class="text-muted">
                        Catat sampah yang kamu setor.
                    </p>
                     <a href="transaksi/tambah.php"
                    class="btn btn-succes">
                Mulai</a>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body p-4">

                    <h5>
                        📜 Riwayat
                    </h5>

                    <p class="text-muted">
                        Lihat transaksi sampah kamu.
                    </p>

                   <a href="riwayat/index.php"
                   class="btn btn-outline-succes">Lihat</a>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body p-4">

                    <h5>
                        💸 Tarik Saldo
                    </h5>

                    <p class="text-muted">
                        Ajukan penarikan saldo.
                    </p>

                    <a href="tarik_saldo/index.php"
                    class="btn btn-succes">
                Tarik</a>

                </div>

            </div>

        </div>


    </div>


    <!-- AKTIVITAS -->

    <div class="card menu-card shadow-sm mt-4">

        <div class="card-body p-4">

            <h5 class="fw-bold">
                Aktivitas Terbaru
            </h5>

            <hr>

           <?php

if (mysqli_num_rows($query_transaksi) > 0) {

    while ($data = mysqli_fetch_assoc($query_transaksi)) {

?>

    <div class="border-bottom py-3">

        <div class="d-flex justify-content-between">

            <div>

                <strong>
                    🗑️ <?php echo $data['jenis_sampah']; ?>
                </strong>

                <br>

                <small class="text-muted">

                    <?php echo $data['berat_kg']; ?> Kg

                    × Rp
                    <?php echo number_format($data['total_rp'] / $data['berat_kg'], 0, ',', '.'); ?>/Kg

                    <br>

                    <?php echo $data['tanggal']; ?>

                </small>

            </div>


            <div class="text-success fw-bold">

                + Rp
                <?php echo number_format($data['total_rp'], 0, ',', '.'); ?>

            </div>

        </div>

    </div>

<?php

    }

} else {

?>

    <p class="text-muted mb-0">
        Belum ada transaksi.
    </p>

<?php

}

?>

        </div>

    </div>


</div>


</body>

</html>