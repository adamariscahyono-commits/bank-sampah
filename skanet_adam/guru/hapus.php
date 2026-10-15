<?php
include "../koneksi.php";

$nip = $_GET['nip'];

$query = mysqli_query($koneksi, 
"DELETE FROM guru WHERE nip='$nip'"
);

if($query){
    header("location:../index.php?page=guru/index");
    exit;
}else{
    echo "Data gagal dihapus";
}
?>  