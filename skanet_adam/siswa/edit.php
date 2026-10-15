<?php
include "koneksi.php";

$nis=$_GET['nis'];

$query=mysqli_query($koneksi,
"SELECT * FROM siswa WHERE nis='$nis'");

$data=mysqli_fetch_array($query);
?>


<style>

body{
    background:#eef2f7;
}


/* CARD */
.edit-card{

    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.15);

}



/* HEADER */

.edit-header{

    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    padding:30px;
    color:white;
    display:flex;
    align-items:center;
    gap:20px;

}



/* FORM */

.form-label{

    font-weight:600;
    color:#1e293b;

}


.form-control{

    border-radius:12px;
    padding:12px;
    border:1px solid #cbd5e1;

}


.form-control:focus{

    border-color:#2563eb;
    box-shadow:0 0 8px rgba(37,99,235,.3);

}



/* BUTTON */

.btn-simpan{

    background:#2563eb;
    color:white;
    border-radius:30px;
    padding:10px 30px;

}


.btn-simpan:hover{

    background:#1d4ed8;
    color:white;

}



.btn-kembali{

    background:#64748b;
    color:white;
    border-radius:30px;
    padding:10px 30px;

}


.btn-kembali:hover{

    background:#475569;
    color:white;

}


</style>



<div class="container mt-5">


<div class="edit-card">



<div class="edit-header">


<div class="logo-box">

<i class="bi bi-person-vcard-fill"></i>

</div>


<div>

<h2 class="mb-1">
Edit Data Siswa
</h2>

<p class="mb-0">
Sistem Informasi Sekolah Adam Aris
</p>

</div>


</div>





<div class="p-4">


<form action="update.php" method="POST">



<div class="mb-3">

<label class="form-label">
<i class="bi bi-card-heading text-primary"></i>
NIS
</label>

<input type="text"
name="nis"
class="form-control"
value="<?= $data['nis']; ?>"
readonly>

</div>




<div class="mb-3">

<label class="form-label">

<i class="bi bi-person-fill text-primary"></i>
Nama Siswa

</label>


<input type="text"
name="nama"
class="form-control"
value="<?= $data['nama']; ?>">

</div>





<div class="mb-3">

<label class="form-label">

<i class="bi bi-geo-alt-fill text-primary"></i>
Alamat

</label>


<textarea 
name="alamat"
class="form-control"
rows="3"><?= $data['alamat']; ?></textarea>


</div>





<button class="btn btn-simpan">

<i class="bi bi-save-fill"></i>
Simpan

</button>



<a href="index.php?page=siswa/index"
class="btn btn-kembali">

<i class="bi bi-arrow-left-circle"></i>
Kembali

</a>



</form>


</div>


</div>


</div>