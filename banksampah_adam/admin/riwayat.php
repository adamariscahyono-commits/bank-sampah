<?php
session_start();
require_once "../config/koneksi.php";

if(!isset($_SESSION["login"]) || $_SESSION["role"]!="admin"){
    header("Location: ../auth/login.php");
    exit;
}

// HAPUS RIWAYAT
if(isset($_GET["hapus"]) && isset($_GET["id"])){

    $id = (int) $_GET["id"];
    $jenis = $_GET["hapus"];

    // Hapus riwayat setoran
    if($jenis == "setor"){
        mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi=$id");
    }

    // Hapus riwayat tarik
    if($jenis == "tarik"){
        mysqli_query($conn, "DELETE FROM tarik_saldo WHERE id_tarik=$id");
    }

    header("Location: riwayat.php");
    exit;
}

// Riwayat Setoran
$riwayatSetor = mysqli_query($conn,"
SELECT transaksi.*, users.nama, sampah.jenis_sampah
FROM transaksi
JOIN users ON transaksi.id_user = users.id_user
JOIN sampah ON transaksi.id_sampah = sampah.id_sampah
WHERE transaksi.status!='pending'
ORDER BY transaksi.id_transaksi DESC
");

// Riwayat Tarik
$riwayatTarik = mysqli_query($conn,"
SELECT tarik_saldo.*, users.nama
FROM tarik_saldo
JOIN users ON tarik_saldo.id_user = users.id_user
WHERE tarik_saldo.status!='pending'
ORDER BY tarik_saldo.id_tarik DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Riwayat"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Arsip</span>
    <h1>Riwayat <span class="grad-text">Transaksi</span></h1>
    <p>Semua setoran dan penarikan yang sudah diproses.</p>
</div>

<h2 class="section-title"><i class="bi bi-recycle"></i> Riwayat Setoran</h2>

<div class="table-wrap">
<table>
<tr>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Jenis Sampah</th>
    <th>Berat</th>
    <th>Total</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($riwayatSetor) > 0){ ?>
<?php while($r=mysqli_fetch_assoc($riwayatSetor)){ ?>
<tr>
    <td><?= $r["tanggal"] ?></td>
    <td><span class="strong"><?= $r["nama"] ?></span></td>
    <td><?= $r["jenis_sampah"] ?></td>
    <td><?= $r["berat_kg"] ?> Kg</td>
    <td>Rp <?= number_format($r["total_rp"]) ?></td>
    <td><span class="badge st-<?= strtolower($r["status"]) ?>"><?= ucfirst($r["status"]) ?></span></td>
    <td>
        <a href="riwayat.php?hapus=setor&id=<?= $r["id_transaksi"] ?>"
           class="btn btn-bad btn-sm"
           onclick="return confirm('Yakin ingin menghapus riwayat setoran ini?')">
            <i class="bi bi-trash3"></i> Hapus
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="7" class="empty"><i class="bi bi-inbox"></i>Belum ada riwayat setoran.</td></tr>
<?php } ?>

</table>
</div>

<h2 class="section-title"><i class="bi bi-cash-coin"></i> Riwayat Tarik Saldo</h2>

<div class="table-wrap">
<table>
<tr>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Nominal</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($riwayatTarik) > 0){ ?>
<?php while($t=mysqli_fetch_assoc($riwayatTarik)){ ?>
<tr>
    <td><?= $t["tanggal"] ?></td>
    <td><span class="strong"><?= $t["nama"] ?></span></td>
    <td>Rp <?= number_format($t["nominal"]) ?></td>
    <td><span class="badge st-<?= strtolower($t["status"]) ?>"><?= ucfirst($t["status"]) ?></span></td>
    <td>
        <a href="riwayat.php?hapus=tarik&id=<?= $t["id_tarik"] ?>"
           class="btn btn-bad btn-sm"
           onclick="return confirm('Yakin ingin menghapus riwayat penarikan ini?')">
            <i class="bi bi-trash3"></i> Hapus
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="5" class="empty"><i class="bi bi-inbox"></i>Belum ada riwayat penarikan.</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>
