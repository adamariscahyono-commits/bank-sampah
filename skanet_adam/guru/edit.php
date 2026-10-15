<?php
include "../koneksi.php";

$nip = $_GET['nip'];

$query = mysqli_query($koneksi,
"SELECT * FROM guru WHERE nip='$nip'");

$data = mysqli_fetch_array($query);
?>


<style>

body{
    background:linear-gradient(135deg,#e0f2fe,#f8fafc);
}


/* CARD */

.edit-box{

    max-width:700px;
    margin:40px auto;
    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.15);

}



/* HEADER */

.edit-header{

    background:linear-gradient(135deg,#064e3b,#059669);
    padding:35px;
    color:white;
    text-align:center;

}




.form-area{

    padding:35px;

}



.form-label{

    font-weight:700;
    color:#334155;

}



.form-control{

    border-radius:15px;

    padding:12px;

    border:2px solid #e2e8f0;

}



.form-control:focus{

    border-color:#059669;

    box-shadow:0 0 10px rgba(5,150,105,.3);

}



/* BUTTON */

.btn-update{

    background:linear-gradient(135deg,#059669,#047857);

    color:white;

    border:none;

    border-radius:30px;

    padding:12px 35px;

    font-weight:bold;

}



.btn-update:hover{

    background:#065f46;

    color:white;

}



.btn-back{

    background:#64748b;

    color:white;

    border-radius:30px;

    padding:12px 30px;

}


.btn-back:hover{

    background:#475569;

    color:white;

}

</style>





<div class="edit-box">


<div class="edit-header">


<div class="logo-guru">

<i class="bi bi-person-workspace"></i>

</div>


<h2 class="mt-3">

Edit Data Guru

</h2>


<p>

Sistem Informasi Sekolah Adam Aris

</p>


</div>





<div class="form-area">


<form action="update.php" method="POST">



<div class="mb-4">

<label class="form-label">

<i class="bi bi-person-vcard text-success"></i>

NIP

</label>


<input type="text"
name="nip"
class="form-control"
value="<?= $data['nip']; ?>"
readonly>

</div>





<div class="mb-4">

<label class="form-label">

<i class="bi bi-person-fill text-success"></i>

Nama Guru

</label>


<input type="text"
name="nama"
class="form-control"
value="<?= $data['nama']; ?>">

</div>





<div class="mb-4">

<label class="form-label">

<i class="bi bi-geo-alt-fill text-success"></i>

Alamat

</label>


<textarea 
name="alamat"
class="form-control"
rows="4"><?= $data['alamat']; ?></textarea>


</div>





<div class="text-center">


<button class="btn btn-update me-2">

<i class="bi bi-save-fill"></i>

Update Data

</button>



<a href="../index.php?page=guru/index"
class="btn btn-back">

<i class="bi bi-arrow-left-circle"></i>

Kembali

</a>


</div>



</form>


</div>


</div>