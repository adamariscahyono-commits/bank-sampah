<?php
session_start();
require_once "../config/koneksi.php";

if(!isset($_SESSION["login"]) || $_SESSION["role"]!="admin"){
    header("Location: ../auth/login.php");
    exit;
}


// Hitung data dashboard

// Saldo Admin
$saldoAdmin = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT IFNULL(SUM(saldo),0) AS total
        FROM users
        WHERE role='admin'
    ")
)["total"];

$totalUser = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users"))["total"];

$totalSampah = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM sampah"))["total"];

$setorPending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM transaksi WHERE status='pending'"))["total"];

$tarikPending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM tarik_saldo WHERE status='pending'"))["total"];

// Total stok semua sampah (Kg)
$totalStok = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT IFNULL(SUM(stok_kg),0) AS total
        FROM sampah
    ")
)["total"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Dashboard Admin"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Dashboard Admin</span>
    <h1>Halo, <span class="grad-text"><?= htmlspecialchars($_SESSION["nama"]); ?></span> 👋</h1>
    <p>Pantau saldo, stok gudang, dan semua pengajuan yang masuk hari ini.</p>
</div>

<div class="stats">

    <div class="stat hero">
        <div class="chip"><i class="bi bi-wallet2"></i></div>
        <div class="label">Saldo Admin</div>
        <div class="value">Rp <?= number_format($saldoAdmin,0,',','.'); ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-people-fill"></i></div>
        <div class="label">Total User</div>
        <div class="value"><?= $totalUser; ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-recycle"></i></div>
        <div class="label">Jenis Sampah</div>
        <div class="value"><?= $totalSampah; ?></div>
    </div>

    <div class="stat <?= $setorPending > 0 ? "alert" : "" ?>">
        <div class="chip"><i class="bi bi-hourglass-split"></i></div>
        <div class="label">Setoran Pending <?php if($setorPending>0){ ?><span class="dot-live"></span><?php } ?></div>
        <div class="value"><?= $setorPending; ?></div>
    </div>

    <div class="stat <?= $tarikPending > 0 ? "alert" : "" ?>">
        <div class="chip"><i class="bi bi-cash-stack"></i></div>
        <div class="label">Tarik Pending <?php if($tarikPending>0){ ?><span class="dot-live"></span><?php } ?></div>
        <div class="value"><?= $tarikPending; ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-box-seam"></i></div>
        <div class="label">Total Sampah Gudang</div>
        <div class="value"><?= $totalStok ?> <small>Kg</small></div>
    </div>

</div>

</main>
</div>
</body>
</html>
