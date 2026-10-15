<?php

session_start();
require_once "../config/koneksi.php";

// ======================================
// CEK ADMIN
// ======================================

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}


// ======================================
// PROSES SETUJUI / TOLAK
// ======================================

if (isset($_GET["aksi"]) && isset($_GET["id"])) {

    $id = (int) $_GET["id"];
    $aksi = $_GET["aksi"];

    // ======================================
    // AMBIL DATA CHECKOUT
    // ======================================

    $q = mysqli_query($conn, "
        SELECT *
        FROM checkout
        WHERE id_checkout = $id
    ");

    if (!$q) {
        die("Query checkout gagal: " . mysqli_error($conn));
    }

    $checkout = mysqli_fetch_assoc($q);

    if (!$checkout) {

        echo "<script>
                alert('Data checkout tidak ditemukan!');
                location='persetujuan_checkout.php';
              </script>";
        exit;
    }

    $idPengepul = (int) $checkout["id_pengepul"];
    $total = (float) $checkout["total"];


    // ======================================
    // SETUJUI CHECKOUT
    // ======================================

    if ($aksi == "setuju") {

        // ======================================
        // CEK SALDO PENGEPUL
        // ======================================

        $qSaldo = mysqli_query($conn, "
            SELECT saldo
            FROM users
            WHERE id_user = $idPengepul
            AND role = 'pengepul'
        ");

        if (!$qSaldo) {
            die("Query saldo pengepul gagal: " . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($qSaldo);

        if (!$user) {

            echo "<script>
                    alert('Data pengepul tidak ditemukan!');
                    location='persetujuan_checkout.php';
                  </script>";
            exit;
        }

        $saldoPengepul = (float) $user["saldo"];


        // ======================================
        // CEK SALDO PENGEPUL CUKUP
        // ======================================

        if ($saldoPengepul < $total) {

            echo "<script>
                    alert('Saldo pengepul tidak mencukupi!');
                    location='persetujuan_checkout.php';
                  </script>";
            exit;
        }


        // ======================================
        // AMBIL DETAIL CHECKOUT
        // ======================================

        $detail = mysqli_query($conn, "
            SELECT *
            FROM checkout_detail
            WHERE id_checkout = $id
        ");

        if (!$detail) {
            die("Query checkout_detail gagal: " . mysqli_error($conn));
        }

        $items = [];
        $stokCukup = true;


        while ($d = mysqli_fetch_assoc($detail)) {

            $idSampah = (int) $d["id_sampah"];
            $berat = (float) $d["berat"];


            // ======================================
            // CEK STOK SAMPAH
            // ======================================

            $qStok = mysqli_query($conn, "
                SELECT stok_kg
                FROM sampah
                WHERE id_sampah = $idSampah
            ");

            if (!$qStok) {
                die("Query stok gagal: " . mysqli_error($conn));
            }

            $sampah = mysqli_fetch_assoc($qStok);

            if (!$sampah) {
                $stokCukup = false;
                break;
            }

            $stok = (float) $sampah["stok_kg"];

            if ($stok < $berat) {
                $stokCukup = false;
                break;
            }

            $items[] = [
                "id_sampah" => $idSampah,
                "berat" => $berat
            ];
        }


        // ======================================
        // CEK STOK
        // ======================================

        if (!$stokCukup) {

            echo "<script>
                    alert('Stok sampah tidak mencukupi!');
                    location='persetujuan_checkout.php';
                  </script>";
            exit;
        }


        // ======================================
        // CARI ADMIN
        // ======================================

        $qAdmin = mysqli_query($conn, "
            SELECT id_user, saldo
            FROM users
            WHERE role = 'admin'
            ORDER BY id_user ASC
            LIMIT 1
        ");

        if (!$qAdmin) {
            die("Query admin gagal: " . mysqli_error($conn));
        }

        $admin = mysqli_fetch_assoc($qAdmin);

        if (!$admin) {

            echo "<script>
                    alert('Data admin tidak ditemukan!');
                    location='persetujuan_checkout.php';
                  </script>";
            exit;
        }

        $idAdmin = (int) $admin["id_user"];


        // ======================================
        // MULAI TRANSAKSI DATABASE
        // ======================================

        mysqli_begin_transaction($conn);


        try {

            // ======================================
            // 1. KURANGI SALDO PENGEPUL
            // ======================================

            $updatePengepul = mysqli_query($conn, "
                UPDATE users
                SET saldo = saldo - $total
                WHERE id_user = $idPengepul
                AND role = 'pengepul'
            ");

            if (!$updatePengepul) {
                throw new Exception(
                    "Gagal mengurangi saldo pengepul: " .
                    mysqli_error($conn)
                );
            }


            // ======================================
            // 2. TAMBAH SALDO ADMIN
            // ======================================

            $updateAdmin = mysqli_query($conn, "
                UPDATE users
                SET saldo = saldo + $total
                WHERE id_user = $idAdmin
                AND role = 'admin'
            ");

            if (!$updateAdmin) {
                throw new Exception(
                    "Gagal menambah saldo admin: " .
                    mysqli_error($conn)
                );
            }


            // ======================================
            // 3. KURANGI STOK SAMPAH
            // ======================================

            foreach ($items as $item) {

                $idSampah = (int) $item["id_sampah"];
                $berat = (float) $item["berat"];

                $updateStok = mysqli_query($conn, "
                    UPDATE sampah
                    SET stok_kg = stok_kg - $berat
                    WHERE id_sampah = $idSampah
                ");

                if (!$updateStok) {
                    throw new Exception(
                        "Gagal mengurangi stok: " .
                        mysqli_error($conn)
                    );
                }
            }


            // ======================================
            // 4. UBAH STATUS CHECKOUT
            // ======================================

            $updateCheckout = mysqli_query($conn, "
                UPDATE checkout
                SET status_pembayaran = 'Lunas'
                WHERE id_checkout = $id
            ");

            if (!$updateCheckout) {
                throw new Exception(
                    "Gagal mengubah status checkout: " .
                    mysqli_error($conn)
                );
            }


            // ======================================
            // SIMPAN SEMUA PERUBAHAN
            // ======================================

            mysqli_commit($conn);


            echo "<script>
                    alert('Checkout berhasil disetujui! Saldo admin bertambah Rp " .
                    number_format($total, 0, ',', '.') .
                    "');
                    location='persetujuan_checkout.php';
                  </script>";
            exit;

        } catch (Exception $e) {

            // ======================================
            // BATALKAN SEMUA PERUBAHAN
            // ======================================

            mysqli_rollback($conn);

            die($e->getMessage());
        }
    }


    // ======================================
    // TOLAK CHECKOUT
    // ======================================

    if ($aksi == "tolak") {

        $tolak = mysqli_query($conn, "
            UPDATE checkout
            SET status_pembayaran = 'Ditolak'
            WHERE id_checkout = $id
        ");

        if (!$tolak) {
            die("Gagal menolak checkout: " . mysqli_error($conn));
        }

        echo "<script>
                alert('Checkout berhasil ditolak!');
                location='persetujuan_checkout.php';
              </script>";
        exit;
    }
}


// ======================================
// AMBIL CHECKOUT MENUNGGU
// ======================================

$data = mysqli_query($conn, "
    SELECT
        checkout.*,
        users.nama
    FROM checkout
    JOIN users
        ON checkout.id_pengepul = users.id_user
    WHERE LOWER(TRIM(checkout.status_pembayaran)) = 'menunggu'
    ORDER BY checkout.id_checkout DESC
");

if (!$data) {
    die("Query data checkout gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Persetujuan Checkout"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Persetujuan</span>
    <h1>Persetujuan <span class="grad-text">Checkout</span></h1>
    <p>Checkout dari pengepul yang menunggu keputusan kamu.</p>
</div>

<div class="table-wrap">
<table>
<tr>
    <th>ID</th>
    <th>Pengepul</th>
    <th>Tanggal</th>
    <th>Total</th>
    <th>Jumlah Bayar</th>
    <th>Kembalian</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($data) > 0){ ?>

<?php while($r = mysqli_fetch_assoc($data)){ ?>
<tr>
    <td>#<?= $r["id_checkout"]; ?></td>
    <td><span class="strong"><?= htmlspecialchars($r["nama"]); ?></span></td>
    <td><?= date("d/m/Y", strtotime($r["tanggal"])); ?></td>
    <td>Rp <?= number_format($r["total"],0,',','.'); ?></td>
    <td>Rp <?= number_format($r["jumlah_bayar"],0,',','.'); ?></td>
    <td>Rp <?= number_format($r["kembalian"],0,',','.'); ?></td>
    <td>
        <div class="actions">
            <a class="btn btn-ok btn-sm"
               href="?aksi=setuju&id=<?= $r["id_checkout"]; ?>"
               onclick="return confirm('Setujui checkout ini?')">
                <i class="bi bi-check2-circle"></i> Setujui
            </a>
            <a class="btn btn-bad btn-sm"
               href="?aksi=tolak&id=<?= $r["id_checkout"]; ?>"
               onclick="return confirm('Tolak checkout ini?')">
                <i class="bi bi-x-circle"></i> Tolak
            </a>
        </div>
    </td>
</tr>
<?php } ?>

<?php } else { ?>
<tr><td colspan="7" class="empty"><i class="bi bi-inbox"></i>Belum ada checkout yang menunggu persetujuan.</td></tr>
<?php } ?>

</table>
</div>

</main>
</div>
</body>
</html>
