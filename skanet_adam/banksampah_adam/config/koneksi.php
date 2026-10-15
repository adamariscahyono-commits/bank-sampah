<?php


$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "banksampah_adam"
);
if (!$koneksi)  {
    die("koneksi database gagal: " .
    mysqli_connect_error());
}


?>  