<?php

include "koneksi.php";
$id_mapel = $_POST['id_mapel'];
$nama_mapel = $_POST['nama_mapel'];
$guru = $_POST['guru'];

$query = mysqli_query($koneksi,
    "INSERT INTO mapel (id_mapel, nama_mapel, guru)
     VALUES ('$id_mapel', '$nama_mapel', '$guru')"
);

if ($query) {

    header("Location: index.php?page=mapel/index");
    exit;

} else {

    echo "Gagal menyimpan data: " . mysqli_error($koneksi);

}

?>