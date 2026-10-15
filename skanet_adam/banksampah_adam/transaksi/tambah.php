<?php

session_start();

include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Setor Sampah</title>

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

        .btn-submit {
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
            🗑️ Setor Sampah
        </h2>

        <p class="text-muted">
            Catat sampah yang kamu setorkan.
        </p>

    </div>


    <!-- FORM -->

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body p-4">


                    <form action="proses_tambah.php" method="POST">


                        <!-- JENIS SAMPAH -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Jenis Sampah
                            </label>

                            <select
                                name="id_sampah"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Sampah --
                                </option>

                                <?php

                                $query = mysqli_query(
                                    $koneksi,
                                    "SELECT * FROM sampah"
                                );

                                while ($data = mysqli_fetch_assoc($query)) {

                                ?>

                                    <option value="<?php echo $data['id_sampah']; ?>">

                                        <?php echo $data['jenis_sampah']; ?>

                                        - Rp
                                        <?php echo number_format($data['harga_per_kg'], 0, ',', '.'); ?>/Kg

                                    </option>

                                <?php

                                }

                                ?>

                            </select>

                        </div>


                        <!-- BERAT -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Berat Sampah (Kg)
                            </label>

                            <input
                                type="number"
                                name="berat"
                                class="form-control"
                                placeholder="Contoh: 2"
                                min="0.1"
                                step="0.1"
                                required
                            >

                        </div>


                        <!-- TOMBOL -->

                        <button
                            type="submit"
                            class="btn btn-success btn-submit w-100"
                        >
                            Simpan Setoran
                        </button>


                    </form>


                </div>

            </div>

        </div>

    </div>


</div>


</body>

</html>