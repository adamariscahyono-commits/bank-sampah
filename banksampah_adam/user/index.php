<?php
session_start();
require_once "../config/koneksi.php";

if(!isset($_SESSION["login"])){
    header("Location: ../auth/login.php");
    exit;
}

$id=$_SESSION["id_user"];

$user=mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM users WHERE id_user=$id")
);

$totalSetor=mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT IFNULL(SUM(berat_kg),0) total
        FROM transaksi
        WHERE id_user=$id
        AND status='disetujui'
    ")
)["total"];

// Badge sederhana
$badge="Eco Seed";

if($totalSetor>=20){
    $badge="Eco Hero";
}
if($totalSetor>=50){
    $badge="Green Master";
}
if($totalSetor>=100){
    $badge="Planet Saver";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Dashboard"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Dashboard Nasabah</span>
    <h1>Halo, <span class="grad-text"><?= htmlspecialchars($user["nama"]) ?></span> 👋</h1>
    <p>Setor sampah, kumpulkan saldo, dan naikkan level kamu.</p>
</div>

<div class="stats">

    <div class="stat hero">
        <div class="chip"><i class="bi bi-wallet2"></i></div>
        <div class="label">Saldo</div>
        <div class="value">Rp <?= number_format($user["saldo"]) ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-recycle"></i></div>
        <div class="label">Total Sampah Disetor</div>
        <div class="value"><?= $totalSetor ?> <small>Kg</small></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-trophy-fill"></i></div>
        <div class="label">Level</div>
        <div class="value"><?= $badge ?></div>
    </div>

    <div class="stat">
        <div class="chip"><i class="bi bi-patch-check-fill"></i></div>
        <div class="label">Status</div>
        <div class="value">Aktif</div>
    </div>

</div>

<div class="actions" style="margin-top:26px">
    <a class="btn" href="setor.php"><i class="bi bi-recycle"></i> Setor Sampah</a>
    <a class="btn btn-ghost" href="tarik.php"><i class="bi bi-wallet2"></i> Tarik Saldo</a>
</div>

</main>
</div>
</body>
</html>
