
<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$pesan = "";

// =========================
// TAMBAH SAMPAH
// =========================
if (isset($_POST["tambah"])) {

    $jenis = trim($_POST["jenis_sampah"]);
    $harga = (int) $_POST["harga_per_kg"];
    $stok  = (float) $_POST["stok_kg"];

    if ($jenis == "" || $harga <= 0 || $stok < 0) {

        $pesan = "Data sampah tidak valid!";

    } else {

        $query = mysqli_prepare($conn, "
            INSERT INTO sampah
            (jenis_sampah, harga_per_kg, stok_kg)
            VALUES (?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $query,
            "sid",
            $jenis,
            $harga,
            $stok
        );

        if (mysqli_stmt_execute($query)) {

            header("Location: sampah.php?status=berhasil");
            exit;

        } else {

            $pesan = "Gagal menambahkan sampah: " . mysqli_stmt_error($query);

        }
    }
}

// =========================
// HAPUS SAMPAH
// =========================
if (isset($_GET["hapus"])) {

    $id = (int) $_GET["hapus"];

    $cek = mysqli_query($conn, "
        SELECT id_sampah
        FROM transaksi
        WHERE id_sampah = $id
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) > 0) {

        header("Location: sampah.php?status=gagal_hapus");
        exit;

    }

    mysqli_query($conn, "
        DELETE FROM sampah
        WHERE id_sampah = $id
    ");

    header("Location: sampah.php?status=terhapus");
    exit;
}

// =========================
// AMBIL DATA SAMPAH
// =========================
$data = mysqli_query($conn, "
    SELECT *
    FROM sampah
    ORDER BY id_sampah ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php
    $judul = "Kelola Sampah";
    require __DIR__ . "/../partials/head.php";
    ?>
</head>

<body>

<div class="app">

    <?php require __DIR__ . "/../partials/sidebar.php"; ?>

    <main class="main">

        <!-- JUDUL HALAMAN -->
        <div class="page-head">

            <span class="eyebrow">Gudang</span>

            <h1>Kelola <span class="grad-text">Sampah</span></h1>

            <p>Atur jenis sampah, harga per kilo, dan pantau stok.</p>

        </div>

        <!-- NOTIFIKASI -->
        <?php if (isset($_GET["status"]) && $_GET["status"] == "berhasil") { ?>

            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                Sampah berhasil ditambahkan!
            </div>

        <?php } elseif (isset($_GET["status"]) && $_GET["status"] == "terhapus") { ?>

            <div class="alert alert-success">
                Sampah berhasil dihapus!
            </div>

        <?php } elseif (isset($_GET["status"]) && $_GET["status"] == "gagal_hapus") { ?>

            <div class="alert alert-danger">
                Sampah tidak bisa dihapus karena sudah digunakan dalam transaksi!
            </div>

        <?php } ?>

        <?php if ($pesan != "") { ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan) ?>
            </div>

        <?php } ?>

        <!-- FORM TAMBAH SAMPAH -->
        <div class="table-wrap" style="padding: 25px; margin-bottom: 25px;">

            <h3 style="margin-bottom: 20px;">
                <i class="bi bi-plus-circle"></i>
                Tambah Jenis Sampah
            </h3>

            <form method="POST">

                <div style="margin-bottom: 18px;">

                    <label style="display:block; margin-bottom:8px;">
                        Nama Jenis Sampah
                    </label>

                    <input
                        type="text"
                        name="jenis_sampah"
                        placeholder="Contoh: Botol Plastik"
                        required
                        style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                    >

                </div>

                <div style="margin-bottom: 18px;">

                    <label style="display:block; margin-bottom:8px;">
                        Harga Per Kg (Rp)
                    </label>

                    <input
                        type="number"
                        name="harga_per_kg"
                        placeholder="Contoh: 5000"
                        min="1"
                        required
                        style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                    >

                </div>

                <div style="margin-bottom: 20px;">

                    <label style="display:block; margin-bottom:8px;">
                        Stok Sampah (Kg)
                    </label>

                    <input
                        type="number"
                        name="stok_kg"
                        placeholder="Contoh: 10"
                        min="0"
                        step="0.01"
                        required
                        style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                    >

                </div>

                <button
                    type="submit"
                    name="tambah"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Sampah
                </button>

            </form>

        </div>

        <!-- TABEL SAMPAH -->
        <div class="table-wrap">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Jenis Sampah</th>
                    <th>Harga/Kg</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>

                <?php if (mysqli_num_rows($data) > 0) { ?>

                    <?php while ($r = mysqli_fetch_assoc($data)) { ?>

                        <tr>

                            <td>#<?= $r["id_sampah"] ?></td>

                            <td>
                                <span class="strong">
                                    <?= htmlspecialchars($r["jenis_sampah"]) ?>
                                </span>
                            </td>

                            <td>
                                Rp <?= number_format($r["harga_per_kg"], 0, ',', '.') ?>
                            </td>

                            <td>
                                <?= $r["stok_kg"] ?> Kg
                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        class="btn btn-warn btn-sm"
                                        href="edit_sampah.php?id=<?= $r["id_sampah"] ?>"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <a
                                        class="btn btn-bad btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus sampah ini?')"
                                        href="?hapus=<?= $r["id_sampah"] ?>"
                                    >
                                        <i class="bi bi-trash3"></i>
                                        Hapus
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="5" class="empty">
                            <i class="bi bi-inbox"></i>
                            Belum ada jenis sampah.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </main>

</div>

</body>
</html>
