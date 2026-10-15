<?php

session_start();

include "../config/koneksi.php";


// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


// Ambil data user
$id_user = $_SESSION['id_user'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE id_user='$id_user'"
);

$user = mysqli_fetch_assoc($query);

$saldo = $user['saldo'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tarik Saldo | Bank Sampah</title>

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

        .saldo {
            background-color: #198754;
            color: white;
            border-radius: 15px;
        }

        .btn-tarik {
            border-radius: 10px;
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
            💸 Tarik Saldo
        </h2>

        <p class="text-muted">
            Ajukan penarikan saldo kamu.
        </p>

    </div>


    <div class="row justify-content-center">

        <div class="col-md-6">


            <!-- SALDO -->

            <div class="saldo p-4 mb-3">

                <small>
                    Saldo kamu
                </small>

                <h3 class="fw-bold mt-2 mb-0">

                    Rp <?php echo number_format($saldo, 0, ',', '.'); ?>

                </h3>

            </div>


            <!-- FORM -->

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <form action="proses.php" method="POST">


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Jumlah Penarikan
                            </label>

                            <input
                                type="number"
                                name="jumlah"
                                class="form-control"
                                placeholder="Contoh: 10000"
                                min="1000"
                                required
                            >

                            <small class="text-muted">
                                Minimal penarikan Rp1.000
                            </small>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success btn-tarik w-100"
                        >
                            Tarik Saldo
                        </button>


                    </form>

                </div>

            </div>


        </div>

    </div>


</div>


</body>

</html>