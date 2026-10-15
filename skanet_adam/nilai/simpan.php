<?php
include "koneksi.php";
$nis=$_POST['nis'];
$nip=$_POST['nip'];
$id_mapel=$_POST['id_mapel'];
$nilai=$_POST['nilai'];
$query=mysqli_query($koneksi,"insert into nilai(nis,nip,id_mapel,nilai) values('$nis','$nip','$id_mapel','$nilai')");
 header("location:index.php?page=nilai/index");
    exit;
?>