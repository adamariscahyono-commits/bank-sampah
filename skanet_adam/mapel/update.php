<?php

include __DIR__ . "/../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_mapel = $_POST['id_mapel'];
    $nama_mapel = $_POST['nama_mapel'];
    $guru = $_POST['guru'];

    $query = mysqli_query(
        $koneksi,
        "UPDATE mapel SET 
            nama_mapel='$nama_mapel',
            guru='$guru'
        WHERE id_mapel='$id_mapel'"
    );

    if ($query) {

        header("Location: index.php?page=mapel/index");
        exit;

    } else {

        echo "Data gagal diupdate: " . mysqli_error($koneksi);

    }

} else {

    header("Location: index.php?page=mapel/index");
    exit;

}

?>