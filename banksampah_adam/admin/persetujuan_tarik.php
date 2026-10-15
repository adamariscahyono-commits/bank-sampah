<?php
session_start();
require_once "../config/koneksi.php";

// Cek login admin
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// ===========================
// PROSES SETUJUI / TOLAK
// ===========================
if (isset($_GET["aksi"]) && isset($_GET["id"])) {

    $id   = (int) $_GET["id"];
    $aksi = $_GET["aksi"];

    // Ambil data pengajuan
    $q = mysqli_query($conn, "SELECT * FROM tarik_saldo WHERE id_tarik=$id");
    $pengajuan = mysqli_fetch_assoc($q);

    if ($pengajuan) {

        $idUser  = $pengajuan["id_user"];
        $nominal = $pengajuan["nominal"];

        if ($aksi == "setuju") {

            // Ambil saldo user
            $qSaldo = mysqli_query($conn, "SELECT saldo FROM users WHERE id_user=$idUser");
            $user = mysqli_fetch_assoc($qSaldo);

            // Jika saldo cukup
            if ($user["saldo"] >= $nominal) {

                mysqli_query($conn, "
                    UPDATE users
                    SET saldo = saldo - $nominal
                    WHERE id_user = $idUser
                ");

                mysqli_query($conn, "
                    UPDATE tarik_saldo
                    SET status='diterima'
                    WHERE id_tarik=$id
                ");
            }

        } elseif ($aksi == "tolak") {

            mysqli_query($conn, "
                UPDATE tarik_saldo
                SET status='ditolak'
                WHERE id_tarik=$id
            ");
        }
    }

    header("Location: persetujuan_tarik.php");
    exit;
}

// ===========================
// DATA PENGAJUAN PENDING
// ===========================
$data = mysqli_query($conn, "
SELECT tarik_saldo.*, users.nama
FROM tarik_saldo
JOIN users ON tarik_saldo.id_user = users.id_user
WHERE tarik_saldo.status='pending'
ORDER BY tarik_saldo.id_tarik DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Persetujuan Tarik"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Persetujuan</span>
    <h1>Persetujuan <span class="grad-text">Tarik Saldo</span></h1>
    <p>Saldo nasabah dipotong otomatis saat penarikan disetujui.</p>
</div>

<div class="table-wrap">
<table>
<tr>
    <th>Nama</th>
    <th>Tanggal</th>
    <th>Nominal</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($data)>0){ ?>

<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><span class="strong"><?= $r["nama"]; ?></span></td>
    <td><?= $r["tanggal"]; ?></td>
    <td>Rp <?= number_format($r["nominal"]); ?></td>
    <td>
        <div class="actions">
            <a class="btn btn-ok btn-sm" href="?aksi=setuju&id=<?= $r["id_tarik"]; ?>">
                <i class="bi bi-check2-circle"></i> Setujui
            </a>
            <a class="btn btn-bad btn-sm" href="?aksi=tolak&id=<?= $r["id_tarik"]; ?>">
                <i class="bi bi-x-circle"></i> Tolak
            </a>
        </div>
    </td>
</tr>
<?php } ?>

<?php } else { ?>
<tr><td colspan="4" class="empty"><i class="bi bi-inbox"></i>Belum ada pengajuan tarik saldo.</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>
