<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// ======================================
// PROSES SETUJUI / TOLAK
// ======================================
if (isset($_GET["aksi"]) && isset($_GET["id"])) {

    $id = (int) $_GET["id"];
    $aksi = $_GET["aksi"];

    // Ambil transaksi
    $ambil = mysqli_query($conn, "
        SELECT *
        FROM transaksi
        WHERE id_transaksi = $id
    ");

    if (!$ambil) {
        die("Query transaksi gagal: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($ambil);

    if (!$data) {
        echo "<script>
                alert('Data transaksi tidak ditemukan!');
                location='persetujuan_setor.php';
              </script>";
        exit;
    }

    // ======================================
    // SETUJUI
    // ======================================
    if ($aksi == "setuju") {

        // Pastikan transaksi masih pending
        if ($data["status"] != "pending") {
            echo "<script>
                    alert('Transaksi ini sudah diproses!');
                    location='persetujuan_setor.php';
                  </script>";
            exit;
        }

        $idUser = (int) $data["id_user"];
        $idSampah = (int) $data["id_sampah"];
        $berat = (float) $data["berat_kg"];
        $total = (float) $data["total_rp"];

        // ======================================
        // CEK DATA SAMPAH
        // ======================================
        $cekSampah = mysqli_query($conn, "
            SELECT *
            FROM sampah
            WHERE id_sampah = $idSampah
        ");

        if (!$cekSampah) {
            die("Query sampah gagal: " . mysqli_error($conn));
        }

        $sampah = mysqli_fetch_assoc($cekSampah);

        if (!$sampah) {
            echo "<script>
                    alert('Data jenis sampah tidak ditemukan!');
                    location='persetujuan_setor.php';
                  </script>";
            exit;
        }

        // ======================================
        // 1. UBAH STATUS TRANSAKSI
        // ======================================
        $updateTransaksi = mysqli_query($conn, "
            UPDATE transaksi
            SET status = 'disetujui'
            WHERE id_transaksi = $id
        ");

        if (!$updateTransaksi) {
            die("Gagal update transaksi: " . mysqli_error($conn));
        }

        // ======================================
        // 2. TAMBAHKAN SALDO USER
        // ======================================
        $updateUser = mysqli_query($conn, "
            UPDATE users
            SET saldo = saldo + $total
            WHERE id_user = $idUser
        ");

        if (!$updateUser) {
            die("Gagal update saldo user: " . mysqli_error($conn));
        }

        // ======================================
        // 3. TAMBAHKAN STOK SAMPAH
        // ======================================
        $updateStok = mysqli_query($conn, "
            UPDATE sampah
            SET stok_kg = stok_kg + $berat
            WHERE id_sampah = $idSampah
        ");

        if (!$updateStok) {
            die("Gagal update stok sampah: " . mysqli_error($conn));
        }

        // ======================================
        // SELESAI
        // ======================================
        echo "<script>
                alert('Setoran berhasil disetujui! Saldo user dan stok sampah sudah bertambah.');
                location='persetujuan_setor.php';
              </script>";
        exit;
    }

    // ======================================
    // TOLAK
    // ======================================
    if ($aksi == "tolak") {

        if ($data["status"] != "pending") {
            echo "<script>
                    alert('Transaksi ini sudah diproses!');
                    location='persetujuan_setor.php';
                  </script>";
            exit;
        }

        $update = mysqli_query($conn, "
            UPDATE transaksi
            SET status = 'ditolak'
            WHERE id_transaksi = $id
        ");

        if (!$update) {
            die("Gagal menolak transaksi: " . mysqli_error($conn));
        }

        echo "<script>
                alert('Setoran berhasil ditolak!');
                location='persetujuan_setor.php';
              </script>";
        exit;
    }
}


// ======================================
// DATA TRANSAKSI PENDING
// ======================================
$data = mysqli_query($conn, "
    SELECT 
        transaksi.*,
        users.nama,
        sampah.jenis_sampah
    FROM transaksi
    JOIN users 
        ON transaksi.id_user = users.id_user
    JOIN sampah 
        ON transaksi.id_sampah = sampah.id_sampah
    WHERE transaksi.status = 'pending'
    ORDER BY transaksi.id_transaksi DESC
");

if (!$data) {
    die("Query data pending gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Persetujuan Setor"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Persetujuan</span>
    <h1>Persetujuan <span class="grad-text">Setoran</span></h1>
    <p>Setujui untuk menambah saldo nasabah dan stok gudang.</p>
</div>

<div class="table-wrap">
<table>
<tr>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Jenis</th>
    <th>Berat</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>

<?php while($r = mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $r["tanggal"] ?></td>
    <td><span class="strong"><?= htmlspecialchars($r["nama"]) ?></span></td>
    <td><?= htmlspecialchars($r["jenis_sampah"]) ?></td>
    <td><?= $r["berat_kg"] ?> Kg</td>
    <td>Rp <?= number_format($r["total_rp"], 0, ',', '.') ?></td>
    <td>
        <div class="actions">
            <a class="btn btn-ok btn-sm"
               href="?aksi=setuju&id=<?= $r["id_transaksi"] ?>"
               onclick="return confirm('Setujui setoran ini? Stok sampah akan ditambahkan.')">
                <i class="bi bi-check2-circle"></i> Setujui
            </a>
            <a class="btn btn-bad btn-sm"
               href="?aksi=tolak&id=<?= $r["id_transaksi"] ?>"
               onclick="return confirm('Tolak setoran ini?')">
                <i class="bi bi-x-circle"></i> Tolak
            </a>
        </div>
    </td>
</tr>
<?php } ?>

<?php } else { ?>
<tr><td colspan="6" class="empty"><i class="bi bi-inbox"></i>Belum ada setoran yang menunggu persetujuan.</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>
