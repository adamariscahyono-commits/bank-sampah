<?php
include "koneksi.php";
$id_nilai = $_GET['id_nilai'];
$query = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_nilai = '$id_nilai'");
$data = mysqli_fetch_array($query);
?>
<h1>Edit Data</h1>
<form action="update.php" method="POST">
    <input type="hidden" name="id_nilai" value="<?php echo $data['id_nilai']; ?>">
    Id Mapel :
    <input type="text" name="id_mapel" value="<?php echo $data['id_mapel']; ?>">
    <br>
    Nis : 
    <input type="text" name="nis" value="<?php echo $data['nis']; ?>">
    <br>
    Nip : 
    <input type="text" name="nip" value="<?php echo $data['nip']; ?>">
    <br>
    Nilai :
    <input type="text" name="nilai" value="<?php echo $data['nilai']; ?>">
    <br><br>
    <button type="submit">Simpan</button>
</form>