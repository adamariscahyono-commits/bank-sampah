<?php
include "koneksi.php";
?>

<h1>Data Nilai</h1>
<a href="?page=nilai/tambah">Tambah Data</a>
<br><br>

<table class="table" border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Siswa</th> <th>Nama Mapel</th>
        <th>Nama Guru</th>  <th>Nilai</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    $query = mysqli_query($koneksi, " 
        SELECT nilai.*, siswa.nama AS nama_siswa, mapel.nama_mapel, guru.nama AS nama_guru 
        FROM nilai 
        JOIN siswa ON nilai.nis = siswa.nis
        JOIN mapel ON nilai.id_mapel = mapel.id_mapel
        JOIN guru ON nilai.nip = guru.nip
    ");

    while ($data = mysqli_fetch_array($query)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['nama_siswa']; ?></td>
        <td><?php echo $data['nama_mapel']; ?></td>
        <td><?php echo $data['nama_guru']; ?></td>
        <td><?php echo $data['nilai']; ?></td>
        <td>
          
            <a href="nilai/hapus.php?id_nilai=<?php echo $data['id_nilai']; ?>"  class="btn btn-danger btn-sm" onclick="return confirm('Hapus Data?')">
                 <i class="bi bi-trash-fill"></i>Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>