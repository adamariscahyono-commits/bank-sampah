<?php
include "koneksi.php";
$nip=$_POST['nip'];
$nama=$_POST['nama'];
$alamat=$_POST['alamat'];
$query=mysqli_query($koneksi,"insert into guru(nip,nama,alamat) values('$nip','$nama','$alamat')");
   header("location:../index.php?page=guru/index");
    exit;
?>