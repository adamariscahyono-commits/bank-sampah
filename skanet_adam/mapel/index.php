<?php
include "koneksi.php";

$query = mysqli_query($koneksi,"SELECT * FROM mapel");
?>

<h2>Data Mapel</h2>

<a href="?page=mapel/tambah">Tambah Data</a>

<table class="table" border="1" cellpadding="8">
<tr>
    <th>No</th>
    <th>ID</th>
    <th>Nama Mapel</th>
    <th>Guru</th>
    <th>Aksi</th>
</tr>

<?php
$no=1;
while($data=mysqli_fetch_array($query)){
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $data['id_mapel'] ?></td>
<td><?= $data['nama_mapel'] ?></td>
<td><?= $data['guru'] ?></td>
<td>
<a href="?page=mapel/edit&id_mapel=<?php echo $data['id_mapel']; ?>" class="btn btn-warning">
    Edit
</a>
<a href="mapel/hapus.php?id_mapel=<?= $data['id_mapel'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus Data?')">
                 <i class="bi bi-trash-fill"></i>Hapus</a>
</td>
</tr>

<?php } ?>

</table>