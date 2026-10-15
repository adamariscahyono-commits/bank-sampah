<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "pengepul") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET["id"])) {
    die("Invoice tidak ditemukan.");
}

$id = (int)$_GET["id"];

// Ambil data checkout
$qCheckout = mysqli_query($conn, "
SELECT c.*, u.nama
FROM checkout c
JOIN users u ON c.id_pengepul = u.id_user
WHERE c.id_checkout = $id
");

if (!$qCheckout) {
    die("SQL Error: " . mysqli_error($conn));
}

$checkout = mysqli_fetch_assoc($qCheckout);

if (!$checkout) {
    die("Data checkout tidak ditemukan.");
}

// Ambil detail barang (jika ada)
$qDetail = mysqli_query($conn, "
SELECT cd.*, s.jenis_sampah
FROM checkout_detail cd
JOIN sampah s ON cd.id_sampah = s.id_sampah
WHERE cd.id_checkout = $id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Struk Checkout"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body class="receipt-body">

<div class="receipt">

    <h2>🌱 Nota Pembayaran</h2>
    <div class="inv">INV-<?= str_pad($checkout["id_checkout"],4,"0",STR_PAD_LEFT); ?></div>

    <div class="r-row"><span>Tanggal</span><b><?= $checkout["tanggal"]; ?></b></div>
    <div class="r-row"><span>Pengepul</span><b><?= $checkout["nama"]; ?></b></div>
    <div class="r-row">
        <span>Status</span>
        <span class="badge st-<?= strtolower(trim($checkout["status_pembayaran"])); ?>"><?= $checkout["status_pembayaran"]; ?></span>
    </div>

    <hr>

    <h3 style="font-size:15px;margin-bottom:6px">Detail Sampah</h3>

    <table>
    <?php
    if($qDetail && mysqli_num_rows($qDetail)>0){
        while($d=mysqli_fetch_assoc($qDetail)){
    ?>
        <tr>
            <td><?= $d["jenis_sampah"]; ?></td>
            <td><?= $d["berat"]; ?> kg</td>
            <td align="right">Rp <?= number_format($d["subtotal"]); ?></td>
        </tr>
    <?php
        }
    }else{
    ?>
        <tr><td colspan="3" align="center">Tidak ada detail sampah.</td></tr>
    <?php } ?>
    </table>

    <hr>

    <div class="r-row big"><span>Total</span><b>Rp <?= number_format($checkout["total"]); ?></b></div>
    <div class="r-row"><span>Bayar</span><b>Rp <?= number_format($checkout["jumlah_bayar"]); ?></b></div>
    <div class="r-row"><span>Kembalian</span><b>Rp <?= number_format($checkout["kembalian"]); ?></b></div>

    <div class="actions no-print" style="margin-top:20px">
        <a class="btn" href="index.php" style="flex:1"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button class="btn btn-ghost" type="button" onclick="window.print()" style="flex:1">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>

</div>

</body>
</html>
