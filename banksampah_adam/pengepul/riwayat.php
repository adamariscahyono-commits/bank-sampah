<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "pengepul") {
    header("Location: ../auth/login.php");
    exit;
}

$idPengepul = $_SESSION["id_user"];

$data = mysqli_query($conn,"
SELECT *
FROM checkout
WHERE id_pengepul=$idPengepul
ORDER BY id_checkout DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Riwayat Checkout"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Arsip</span>
    <h1>Riwayat <span class="grad-text">Checkout</span></h1>
    <p>Semua invoice checkout yang pernah kamu ajukan.</p>
</div>

<div class="table-wrap">
<table>
<tr>
    <th>Invoice</th>
    <th>Tanggal</th>
    <th>Total</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>
<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><span class="strong">INV-<?= str_pad($r["id_checkout"],4,"0",STR_PAD_LEFT); ?></span></td>
    <td><?= $r["tanggal"]; ?></td>
    <td>Rp <?= number_format($r["total"]); ?></td>
    <td><span class="badge st-<?= strtolower(trim($r["status_pembayaran"])); ?>"><?= $r["status_pembayaran"]; ?></span></td>
    <td>
        <a class="btn btn-sm" href="struk.php?id=<?= $r["id_checkout"]; ?>">
            <i class="bi bi-eye"></i> Lihat
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="5" class="empty"><i class="bi bi-inbox"></i>Belum ada checkout.</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>