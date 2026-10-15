<?php
session_start();
require_once "../config/koneksi.php";

if(!isset($_SESSION["login"])){
    header("Location: ../auth/login.php");
    exit;
}

if(isset($_POST["setor"])){

    $idUser=$_SESSION["id_user"];
    $idSampah=$_POST["id_sampah"];
    $berat=$_POST["berat"];

    $sampah=mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT *
            FROM sampah
            WHERE id_sampah=$idSampah
        ")
    );

    $total=$berat*$sampah["harga_per_kg"];

    mysqli_query($conn,"
        INSERT INTO transaksi
        (id_user,id_sampah,tanggal,tipe,berat_kg,total_rp,status)
        VALUES
        ($idUser,$idSampah,CURDATE(),'setor',$berat,$total,'pending')
    ");

    echo "<script>alert('Setoran berhasil dikirim!');location='riwayat.php';</script>";
}

$dataSampah=mysqli_query($conn,"SELECT * FROM sampah");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Setor Sampah"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Setor</span>
    <h1>Setor <span class="grad-text">Sampah</span></h1>
    <p>Pilih jenis sampah dan berat, admin akan memverifikasi setoranmu.</p>
</div>

<div class="panel form-card">

<form method="POST">

    <div class="field">
        <label>Jenis Sampah</label>
        <select name="id_sampah">
        <?php while($s=mysqli_fetch_assoc($dataSampah)){ ?>
            <option value="<?= $s["id_sampah"] ?>">
                <?= $s["jenis_sampah"] ?> (Rp <?= number_format($s["harga_per_kg"]) ?>/kg)
            </option>
        <?php } ?>
        </select>
    </div>

    <div class="field">
        <label>Berat (Kg)</label>
        <input type="number" name="berat" step="0.1" placeholder="Contoh: 2.5" required>
    </div>

    <div class="actions">
        <button class="btn" name="setor"><i class="bi bi-send-fill"></i> Kirim Setoran</button>
        <a class="btn btn-ghost" href="index.php">Batal</a>
    </div>

</form>

</div>

</main>
</div>
</body>
</html>
