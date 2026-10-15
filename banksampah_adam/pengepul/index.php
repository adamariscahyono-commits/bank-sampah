<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "pengepul") {
    header("Location: ../auth/login.php");
    exit;
}

$idPengepul = (int)$_SESSION["id_user"];

// Data pengepul
$qUser = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$idPengepul");
$user = mysqli_fetch_assoc($qUser);

$saldo = $user["saldo"] ?? 0;

// Total jenis sampah tersedia
$qJenis = mysqli_query($conn, "SELECT COUNT(*) AS total FROM sampah WHERE stok_kg > 0");
$totalJenis = $qJenis ? mysqli_fetch_assoc($qJenis)["total"] : 0;

// Total stok gudang
$qStok = mysqli_query($conn, "SELECT IFNULL(SUM(stok_kg),0) AS total FROM sampah");
$totalStok = $qStok ? mysqli_fetch_assoc($qStok)["total"] : 0;

// Total checkout saya
$qCheckout = mysqli_query($conn, "SELECT COUNT(*) AS total FROM checkout WHERE id_pengepul=$idPengepul");
$totalCheckout = $qCheckout ? mysqli_fetch_assoc($qCheckout)["total"] : 0;

// Total pembayaran saya
$qBayar = mysqli_query($conn, "SELECT IFNULL(SUM(total),0) AS total FROM checkout WHERE id_pengepul=$idPengepul");
$totalBayar = $qBayar ? mysqli_fetch_assoc($qBayar)["total"] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Dashboard Pengepul"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Dashboard Pengepul</span>
    <h1>Halo, <span class="grad-text"><?= htmlspecialchars($user["nama"]); ?></span> 👋</h1>
    <p>Lihat stok gudang yang tersedia dan kelola checkout kamu.</p>
</div>

<div class="stats">

    <div class="stat hero">
        <div class="chip"><i class="bi bi-wallet2"></i></div>
        <div class="label">Saldo Pengepul</div>
        <div class="value">Rp <?= number_format($saldo); ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-recycle"></i></div>
        <div class="label">Jenis Sampah Tersedia</div>
        <div class="value"><?= $totalJenis; ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-box-seam"></i></div>
        <div class="label">Total Stok Gudang</div>
        <div class="value"><?= number_format($totalStok,2); ?> <small>Kg</small></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-cart-check"></i></div>
        <div class="label">Checkout Saya</div>
        <div class="value"><?= $totalCheckout; ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-cash-stack"></i></div>
        <div class="label">Total Pembayaran</div>
        <div class="value">Rp <?= number_format($totalBayar); ?></div>
    </div>

</div>

<div class="actions" style="margin-top:26px">
    <a class="btn" href="checkout.php"><i class="bi bi-cart-plus"></i> Mulai Checkout</a>
    <a class="btn btn-ghost" href="riwayat.php"><i class="bi bi-receipt"></i> Lihat Riwayat</a>
</div>

</main>
</div>
</body>
</html>
