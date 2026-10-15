<?php

session_start();

include "../config/koneksi.php";


// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


$id_user = $_SESSION['id_user'];


// Ambil semua transaksi user
$query = mysqli_query(
    $koneksi,
    "SELECT transaksi.*, sampah.jenis_sampah
     FROM transaksi
     JOIN sampah ON transaksi.id_sampah = sampah.id_sampah
     WHERE transaksi.id_user='$id_user'
     ORDER BY transaksi.id_transaksi DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Transaksi</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f5f8f6;
        }

        .card {
            border: none;
            border-radius: 18px;
        }

        .judul {
            color: #198754;
            font-weight: bold;
        }

    </style>

</head>


<body>


<div class="container py-5">


    <!-- JUDUL -->

    <div class="mb-4">

        <a
            href="../dashboard.php"
            class="text-decoration-none"
        >
            ← Kembali
        </a>

        <h2 class="judul mt-3">
            📜 Riwayat Transaksi
        </h2>

        <p class="text-muted">
            Semua transaksi sampah kamu.
        </p>

    </div>


    <!-- TABEL -->

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Tanggal</th>

                            <th>Jenis Sampah</th>

                            <th>Berat</th>

                            <th>Tipe</th>

                            <th>Total</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {

                    ?>

                        <tr>

                            <td>
                                <?php echo $no++; ?>
                            </td>

                            <td>
                                <?php echo $data['tanggal']; ?>
                            </td>

                            <td>
                                🗑️ <?php echo $data['jenis_sampah']; ?>
                            </td>

                            <td>
                                <?php echo $data['berat_kg']; ?> Kg
                            </td>

                            <td>

                                <span class="badge bg-success">

                                    <?php echo $data['tipe']; ?>

                                </span>

                            </td>

                            <td class="fw-bold text-success">

                                Rp
                                <?php echo number_format($data['total_rp'], 0, ',', '.'); ?>

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>


</body>

</html>