<?php

session_start();

include "../config/koneksi.php";


// Cek apakah sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


// Ambil data dari form
$id_user = $_SESSION['id_user'];
$id_sampah = $_POST['id_sampah'];
$berat_kg = $_POST['berat'];


// Cari data sampah
$query_sampah = mysqli_query(
    $koneksi,
    "SELECT * FROM sampah WHERE id_sampah='$id_sampah'"
);


if (!$query_sampah) {
    die("Gagal mengambil data sampah: " . mysqli_error($koneksi));
}


$sampah = mysqli_fetch_assoc($query_sampah);


// Ambil harga per Kg
$harga_per_kg = $sampah['harga_per_kg'];


// Hitung total
$total_rp = $berat_kg * $harga_per_kg;


// Simpan transaksi
$query_transaksi = mysqli_query(
    $koneksi,
    "INSERT INTO transaksi
    (id_user, id_sampah, tanggal, tipe, berat_kg, total_rp)
    VALUES
    ('$id_user', '$id_sampah', CURDATE(), 'setor', '$berat_kg', '$total_rp')"
);


// Cek transaksi
if (!$query_transaksi) {

    die("Setoran gagal disimpan.<br>Error: " . mysqli_error($koneksi));

}


// Tambahkan saldo user
$query_saldo = mysqli_query(
    $koneksi,
    "UPDATE users
    SET saldo = saldo + $total_rp
    WHERE id_user='$id_user'"
);


// Cek saldo
if (!$query_saldo) {

    die("Transaksi tersimpan, tetapi saldo gagal diperbarui.<br>Error: " . mysqli_error($koneksi));

}


// Jika berhasil
header("Location: ../dashboard.php");

exit;

?>