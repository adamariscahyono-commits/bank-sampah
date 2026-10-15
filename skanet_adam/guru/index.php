<?php
include "koneksi.php";

?>
<h1>Data Guru</h1>
<a href="?page=guru/tambah"> Tambah data</a>
<table class="table" border="1">
<tr>
    <th>Nip</th>
    <th>Nama</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>
<?php
$query=mysqli_query($koneksi,"select * from guru");
while ($data=mysqli_fetch_array($query)){
    ?>
    <tr>
        <td><?php echo $data['nip'] ?></td>
        <td><?php echo $data['nama'] ?></td>
        <td><?php echo $data['alamat'] ?></td>
        <td>
            <a href="guru/hapus.php?nip=<?php echo $data['nip']?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus Data?')">
                 <i class="bi bi-trash-fill"></i>Hapus</a>
            <a href="guru/edit.php?nip=<?php echo $data['nip']?>"  class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i>Edit</a>
        </td>
</td>
    </tr>
    
    <?php
}
?>
</table>