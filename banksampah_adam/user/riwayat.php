<?php
session_start();
require_once "../config/koneksi.php";

$id=$_SESSION["id_user"];

$data=mysqli_query($conn,"
SELECT transaksi.*, sampah.jenis_sampah
FROM transaksi
JOIN sampah
ON transaksi.id_sampah=sampah.id_sampah
WHERE transaksi.id_user=$id
ORDER BY transaksi.id_transaksi DESC
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
    <h1>Riwayat <span class="grad-text">Setoran Saya</span></h1>
    <p>Pantau status semua setoran sampah kamu.</p>
</div>

<div class="table-wrap">
<table>
<tr>
    <th>Tanggal</th>
    <th>Jenis</th>
    <th>Berat</th>
    <th>Total</th>
    <th>Status</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>
<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $r["tanggal"] ?></td>
    <td><span class="strong"><?= $r["jenis_sampah"] ?></span></td>
    <td><?= $r["berat_kg"] ?> Kg</td>
    <td>Rp <?= number_format($r["total_rp"]) ?></td>
    <td><span class="badge st-<?= strtolower($r["status"]) ?>"><?= ucfirst($r["status"]) ?></span></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="5" class="empty"><i class="bi bi-inbox"></i>Belum ada setoran. Yuk setor sampah pertamamu!</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>
