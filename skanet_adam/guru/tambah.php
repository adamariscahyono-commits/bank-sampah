<div class="container mt-4">

<div class="card shadow border-0 rounded-4">

<div class="card-header text-white"
style="background:linear-gradient(135deg,#064e3b,#059669);">

<h3>
<i class="bi bi-person-plus-fill"></i>
Tambah Data Guru
</h3>

</div>


<div class="card-body">


<form action="guru/simpan.php" method="post">


<div class="mb-3">

<label class="form-label">
NIP
</label>

<input type="text" 
name="nip"
class="form-control"
placeholder="Masukkan NIP">

</div>



<div class="mb-3">

<label class="form-label">
Nama Guru
</label>

<input type="text"
name="nama"
class="form-control"
placeholder="Masukkan nama guru">

</div>



<div class="mb-3">

<label class="form-label">
Alamat
</label>

<textarea 
name="alamat"
class="form-control"
placeholder="Masukkan alamat"></textarea>

</div>



<button type="submit" class="btn btn-success">

<i class="bi bi-save"></i>
Simpan Data

</button>


<a href="index.php?page=guru/index"
class="btn btn-secondary">

Kembali

</a>


</form>


</div>

</div>

</div>