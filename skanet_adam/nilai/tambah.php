



<?php
include "koneksi.php";
?>

<h1>Tambah Nilai</h1>

<form action="?page=nilai/simpan" method="post">

Nama :
<select name="nis">
<?php
$query = mysqli_query($koneksi, "SELECT * FROM siswa");
while($data = mysqli_fetch_array($query)){
?>
    <option value="<?php echo $data['nis']; ?>">
        <?php echo $data['nama']; ?>
    </option>
<?php } ?>
</select>

<br>

Guru :
<select name="nip">
<?php
$query = mysqli_query($koneksi, "SELECT * FROM guru");
while($data = mysqli_fetch_array($query)){
?>
    <option value="<?php echo $data['nip']; ?>">
        <?php echo $data['nama']; ?>
    </option>
<?php } ?>
</select>

<br>

Mapel :
<select name="id_mapel">
<?php
$query = mysqli_query($koneksi, "SELECT * FROM mapel");
while($data = mysqli_fetch_array($query)){
?>
    <option value="<?php echo $data['id_mapel']; ?>">
        <?php echo $data['nama_mapel']; ?>
    </option>
<?php } ?>
</select>

<br>

Nilai :
<input type="text" name="nilai">

<br>

<button type="submit">Simpan</button>

</form>