<?php
include "koneksi.php";
?>

<h1>Data Siswa</h1>
<a href="?page=siswa/tambah"> Tambah Data</a>
<table class="table" border="1">
<tr>
    <th>Nis</th>
    <th>Nama</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>
<?php
$query=mysqli_query($koneksi,"select * from siswa");
while ($data=mysqli_fetch_array($query)){
    ?>
    <tr>
        <td><?php echo $data['nis'] ?></td>
        <td><?php echo $data['nama'] ?></td>
        <td><?php echo $data['alamat'] ?></td>
        <td>
            <a href="siswa/hapus.php?nis=<?php echo $data['nis']?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus Data?')">
                 <i class="bi bi-trash-fill"></i>Hapus</a>
            <a href="siswa/edit.php?nis=<?php echo $data['nis']?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i>Edit</a>
        </td>
</td>
    </tr>
    
    <?php
}
?>
</table>
