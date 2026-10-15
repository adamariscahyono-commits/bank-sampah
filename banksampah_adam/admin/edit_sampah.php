<?php
session_start();
require_once "../config/koneksi.php";

$id=(int)$_GET["id"];

$data=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM sampah
WHERE id_sampah=$id
"));

if(isset($_POST["update"])){

    $jenis=$_POST["jenis"];
    $harga=$_POST["harga"];

    mysqli_query($conn,"
    UPDATE sampah
    SET
    jenis_sampah='$jenis',
    harga_per_kg=$harga
    WHERE id_sampah=$id
    ");

    header("Location: sampah.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Edit Sampah"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Gudang</span>
    <h1>Edit <span class="grad-text">Sampah</span></h1>
    <p>Ubah nama jenis sampah atau harga per kilonya.</p>
</div>

<div class="panel form-card">

<form method="POST">

    <div class="field">
        <label>Jenis Sampah</label>
        <input type="text" name="jenis"
               value="<?= htmlspecialchars($data["jenis_sampah"]) ?>" required>
    </div>

    <div class="field">
        <label>Harga per Kg</label>
        <input type="number" name="harga"
               value="<?= $data["harga_per_kg"] ?>" required>
    </div>

    <div class="actions">
        <button class="btn" name="update"><i class="bi bi-check2-circle"></i> Update</button>
        <a class="btn btn-ghost" href="sampah.php">Batal</a>
    </div>

</form>

</div>

</main>
</div>
</body>
</html>
