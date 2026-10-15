```php
<?php

session_start();

include "../config/koneksi.php";

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil data
$id_user = $_SESSION['id_user'];
$nominal = $_POST['jumlah'];

// Validasi nominal
if (!is_numeric($nominal) || $nominal <= 0) {
    die("Nominal penarikan tidak valid.");
}

// Ambil saldo terbaru
$query_user = mysqli_query(
    $koneksi,
    "SELECT saldo FROM users WHERE id_user='$id_user'"
);

if (!$query_user) {
    die("Gagal mengambil saldo: " . mysqli_error($koneksi));
}

$user = mysqli_fetch_assoc($query_user);

if (!$user) {
    die("Data user tidak ditemukan.");
}

$saldo = $user['saldo'];

// Cek saldo
if ($nominal > $saldo) {
    die("Saldo kamu tidak mencukupi.");
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {

    // Kurangi saldo user
    $update_saldo = mysqli_query(
        $koneksi,
        "UPDATE users 
         SET saldo = saldo - '$nominal'
         WHERE id_user='$id_user'"
    );

    if (!$update_saldo) {
        throw new Exception(mysqli_error($koneksi));
    }

    // Simpan pengajuan penarikan
    $insert_tarik = mysqli_query(
        $koneksi,
        "INSERT INTO tarik_saldo
        (id_user, nominal, tanggal, status)
        VALUES
        ('$id_user', '$nominal', CURDATE(), 'pending')"
    );

    if (!$insert_tarik) {
        throw new Exception(mysqli_error($koneksi));
    }

    // Jika semuanya berhasil
    mysqli_commit($koneksi);

    echo "Pengajuan penarikan berhasil.<br>";
    echo "Nominal penarikan: Rp " . number_format($nominal, 0, ',', '.') . "<br>";
    echo "Status: Pending<br><br>";

    echo '<a href="../dashboard.php">Kembali ke Dashboard</a>';

} catch (Exception $e) {

    // Batalkan semua perubahan jika ada error
    mysqli_rollback($koneksi);

    echo "Pengajuan gagal disimpan.<br>";
    echo "Error: " . $e->getMessage();
}

?>
```
