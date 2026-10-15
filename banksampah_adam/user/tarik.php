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

if(isset($_POST["tarik"])){

    $nominal=(int)$_POST["nominal"];

    if($nominal<=0){

        echo "<script>alert('Nominal tidak valid');</script>";

    }elseif($nominal>$user["saldo"]){

        echo "<script>alert('Saldo tidak mencukupi');</script>";

    }else{

        mysqli_query($conn,"
        INSERT INTO tarik_saldo
        (id_user,nominal,tanggal,status)
        VALUES
        ($id,$nominal,CURDATE(),'pending')
        ");

        echo "<script>alert('Pengajuan tarik saldo berhasil');location='riwayat.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Tarik Saldo"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Tarik</span>
    <h1>Tarik <span class="grad-text">Saldo</span></h1>
    <p>Ajukan penarikan, admin akan menyetujui setelah dicek.</p>
</div>

<div class="panel form-card">

    <div class="total-box">
        <small>Saldo Saat Ini</small>
        <div class="amount">Rp <?= number_format($user["saldo"]) ?></div>
    </div>

    <form method="POST">

        <div class="field">
            <label>Nominal Penarikan</label>
            <input type="number" name="nominal" placeholder="Contoh: 50000" required>
        </div>

        <div class="actions">
            <button class="btn" name="tarik"><i class="bi bi-cash-coin"></i> Ajukan Penarikan</button>
            <a class="btn btn-ghost" href="index.php">Batal</a>
        </div>

    </form>

</div>

</main>
</div>
</body>
</html>
