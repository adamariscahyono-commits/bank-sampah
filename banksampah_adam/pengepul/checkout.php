<?php

session_start();
require_once "../config/koneksi.php";

// ======================================
// CEK LOGIN PENGEPUL
// ======================================

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "pengepul") {
    header("Location: ../auth/login.php");
    exit;
}


// ======================================
// AMBIL DATA SAMPAH
// ======================================

$data = mysqli_query($conn, "
    SELECT *
    FROM sampah
    WHERE stok_kg > 0
    ORDER BY jenis_sampah ASC
");

if (!$data) {
    die("Gagal mengambil data sampah: " . mysqli_error($conn));
}


// ======================================
// PROSES CHECKOUT
// ======================================

if (isset($_POST["bayar"])) {

    $idPengepul = (int) $_SESSION["id_user"];

    // ==================================
    // CEK JUMLAH BAYAR
    // ==================================

    $bayar = isset($_POST["jumlah_bayar"])
        ? (float) $_POST["jumlah_bayar"]
        : 0;


    // ==================================
    // CEK ADA BARANG YANG DIPILIH
    // ==================================

    if (!isset($_POST["berat"]) || !is_array($_POST["berat"])) {

        echo "<script>
                alert('Belum ada sampah yang dipilih!');
                history.back();
              </script>";
        exit;
    }


    // ==================================
    // AMBIL SALDO PENGEPUL
    // ==================================

    $qSaldo = mysqli_query($conn, "
        SELECT saldo
        FROM users
        WHERE id_user = $idPengepul
        AND role = 'pengepul'
    ");

    if (!$qSaldo) {
        die("Query saldo gagal: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($qSaldo);

    if (!$user) {
        die("Data pengepul tidak ditemukan.");
    }

    $saldoPengepul = (float) $user["saldo"];


    // ==================================
    // HITUNG TOTAL
    // ==================================

    $grandTotal = 0;

    $items = [];


    foreach ($_POST["berat"] as $idSampah => $berat) {

        $idSampah = (int) $idSampah;
        $berat = (float) $berat;


        // Lewati jika 0
        if ($berat <= 0) {
            continue;
        }


        // ==================================
        // AMBIL DATA SAMPAH
        // ==================================

        $qSampah = mysqli_query($conn, "
            SELECT *
            FROM sampah
            WHERE id_sampah = $idSampah
        ");

        if (!$qSampah) {
            die("Query sampah gagal: " . mysqli_error($conn));
        }

        $s = mysqli_fetch_assoc($qSampah);


        if (!$s) {
            continue;
        }


        // ==================================
        // CEK STOK
        // ==================================

        $stok = (float) $s["stok_kg"];


        if ($berat > $stok) {

            echo "<script>
                    alert('Berat sampah melebihi stok " .
                    htmlspecialchars($s["jenis_sampah"]) .
                    "!');
                    history.back();
                  </script>";
            exit;
        }


        // ==================================
        // HITUNG SUBTOTAL
        // ==================================

        $harga = (float) $s["harga_per_kg"];

        $subtotal = $berat * $harga;

        $grandTotal += $subtotal;


        // ==================================
        // SIMPAN KE ARRAY
        // ==================================

        $items[] = [
            "id_sampah" => $idSampah,
            "berat" => $berat,
            "harga" => $harga,
            "subtotal" => $subtotal
        ];
    }


    // ==================================
    // CEK BARANG
    // ==================================

    if (count($items) == 0 || $grandTotal <= 0) {

        echo "<script>
                alert('Silakan pilih minimal satu sampah!');
                history.back();
              </script>";
        exit;
    }


    // ==================================
    // CEK SALDO
    // ==================================

    if ($saldoPengepul < $grandTotal) {

        echo "<script>
                alert(
                    'Saldo tidak mencukupi!\\n\\n' +
                    'Saldo Anda: Rp " .
                    number_format($saldoPengepul, 0, ',', '.') .
                    "\\n' +
                    'Total checkout: Rp " .
                    number_format($grandTotal, 0, ',', '.') .
                    "'
                );
                history.back();
              </script>";
        exit;
    }


    // ==================================
    // CEK JUMLAH BAYAR
    // ==================================

    if ($bayar < $grandTotal) {

        echo "<script>
                alert(
                    'Jumlah bayar kurang dari total checkout!\\n\\n' +
                    'Total: Rp " .
                    number_format($grandTotal, 0, ',', '.') .
                    "'
                );
                history.back();
              </script>";
        exit;
    }


    // ==================================
    // HITUNG KEMBALIAN
    // ==================================

    $kembalian = $bayar - $grandTotal;


    // ==================================
    // MULAI TRANSAKSI
    // ==================================

    mysqli_begin_transaction($conn);


    try {

        // ==================================
        // 1. INSERT CHECKOUT
        // HANYA SATU KALI
        // ==================================

        $insertCheckout = mysqli_query($conn, "
            INSERT INTO checkout
            (
                id_pengepul,
                tanggal,
                total,
                metode_pembayaran,
                jumlah_bayar,
                kembalian,
                status_pembayaran
            )
            VALUES
            (
                $idPengepul,
                CURDATE(),
                $grandTotal,
                'Tunai',
                $bayar,
                $kembalian,
                'Menunggu'
            )
        ");


        if (!$insertCheckout) {
            throw new Exception(
                "Gagal menyimpan checkout: " .
                mysqli_error($conn)
            );
        }


        // ==================================
        // AMBIL ID CHECKOUT
        // ==================================

        $idCheckout = mysqli_insert_id($conn);


        // ==================================
        // 2. INSERT CHECKOUT DETAIL
        // BISA BANYAK BARANG
        // ==================================

        foreach ($items as $item) {

            $idSampah = (int) $item["id_sampah"];
            $berat = (float) $item["berat"];


            $insertDetail = mysqli_query($conn, "
                INSERT INTO checkout_detail
                (
                    id_checkout,
                    id_sampah,
                    berat
                )
                VALUES
                (
                    $idCheckout,
                    $idSampah,
                    $berat
                )
            ");


            if (!$insertDetail) {
                throw new Exception(
                    "Gagal menyimpan detail checkout: " .
                    mysqli_error($conn)
                );
            }
        }


        // ==================================
        // JANGAN KURANGI SALDO DI SINI
        //
        // SALDO PENGEPUL AKAN DIKURANGI
        // SAAT ADMIN MENYETUJUI
        // ==================================


        // ==================================
        // JANGAN KURANGI STOK DI SINI
        //
        // STOK AKAN DIKURANGI SAAT ADMIN
        // MENYETUJUI CHECKOUT
        // ==================================


        // ==================================
        // SIMPAN TRANSAKSI
        // ==================================

        mysqli_commit($conn);


        // ==================================
        // BERHASIL
        // ==================================

        echo "<script>

                alert(
                    'Checkout berhasil diajukan!\\n\\n' +
                    'Total: Rp " .
                    number_format($grandTotal, 0, ',', '.') .
                    "\\n' +
                    'Menunggu persetujuan admin.'
                );

                location='struk.php?id=$idCheckout';

              </script>";

        exit;


    } catch (Exception $e) {

        // ==================================
        // JIKA GAGAL
        // BATALKAN SEMUA
        // ==================================

        mysqli_rollback($conn);

        die($e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Checkout Sampah"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<div class="app">
<?php require __DIR__ . "/../partials/sidebar.php"; ?>
<main class="main">

<div class="page-head">
    <span class="eyebrow">Kasir</span>
    <h1>Checkout <span class="grad-text">Sampah</span></h1>
    <p>Pilih jenis dan berat sampah, lalu bayar. Admin akan menyetujui checkout kamu.</p>
</div>

<form method="POST">

<div class="checkout-grid">

    <div class="panel">

        <h2><i class="bi bi-cart3"></i> Daftar Sampah</h2>

        <div class="note"><i class="bi bi-info-circle"></i> Tekan + / − untuk mengatur berat (kg) tiap jenis sampah.</div>

        <?php if(mysqli_num_rows($data) > 0){ ?>
        <?php while($r = mysqli_fetch_assoc($data)){ ?>

        <div class="item">

            <div>
                <strong><?= htmlspecialchars($r["jenis_sampah"]); ?></strong><br>
                <span class="price">Rp <?= number_format($r["harga_per_kg"],0,',','.'); ?>/kg</span><br>
                <small>Stok <?= $r["stok_kg"]; ?> kg</small>
            </div>

            <div class="qty">
                <button type="button"
                        onclick="kurang(<?= $r['id_sampah']; ?>, <?= $r['harga_per_kg']; ?>)">−</button>

                <input readonly
                       id="qty<?= $r['id_sampah']; ?>"
                       name="berat[<?= $r['id_sampah']; ?>]"
                       value="0">

                <button type="button"
                        onclick="tambah(<?= $r['id_sampah']; ?>, <?= $r['harga_per_kg']; ?>, <?= $r['stok_kg']; ?>)">+</button>
            </div>

        </div>

        <?php } ?>
        <?php } else { ?>
            <div class="empty"><i class="bi bi-inbox"></i>Belum ada stok sampah yang tersedia.</div>
        <?php } ?>

    </div>

    <div class="panel pay-panel">

        <h2><i class="bi bi-credit-card-2-front"></i> Pembayaran</h2>

        <div class="total-box">
            <small>Total Belanja</small>
            <div class="amount">Rp <span id="grand">0</span></div>
        </div>

        <div class="field">
            <label>Jumlah Bayar</label>
            <input type="number" name="jumlah_bayar" id="bayar" min="0"
                   placeholder="0" oninput="hitungKembali()" required>
        </div>

        <div class="change">
            <span>Kembalian</span>
            <span>Rp <span id="kembali">0</span></span>
        </div>

        <button class="btn btn-block" style="margin-top:16px" name="bayar" type="submit">
            <i class="bi bi-lightning-charge-fill"></i> Bayar &amp; Checkout
        </button>

        <a class="btn btn-ghost btn-block" style="margin-top:10px;font-size:14px;padding:12px" href="index.php">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

    </div>

</div>

</form>

<script>

// Format Rupiah
function rupiah(angka) {
    return Number(angka).toLocaleString('id-ID');
}

// Menghitung total belanja
function hitungTotal() {

    let total = 0;

    document.querySelectorAll('.qty input').forEach(function(input) {

        let id = input.id.replace('qty', '');
        let berat = parseFloat(input.value) || 0;

        let harga = window.hargaSampah[id] || 0;

        total += berat * harga;

    });

    document.getElementById('grand').innerText = rupiah(total);

    hitungKembali();
}

// Menambah berat sampah
function tambah(id, harga, stok) {

    let input = document.getElementById('qty' + id);

    let berat = parseFloat(input.value) || 0;

    if (berat + 1 > stok) {

        alert('Berat melebihi stok yang tersedia!');

        return;
    }

    input.value = berat + 1;

    hitungTotal();
}

// Mengurangi berat sampah
function kurang(id, harga) {

    let input = document.getElementById('qty' + id);

    let berat = parseFloat(input.value) || 0;

    if (berat > 0) {

        input.value = berat - 1;

    }

    hitungTotal();
}

// Menghitung kembalian
function hitungKembali() {

    let total = 0;

    let teksTotal = document.getElementById('grand').innerText;

    total = Number(teksTotal.replace(/\./g, ''));

    let bayar = parseFloat(
        document.getElementById('bayar').value
    ) || 0;

    let kembali = bayar - total;

    if (kembali < 0) {
        kembali = 0;
    }

    document.getElementById('kembali').innerText =
        rupiah(kembali);
}

// Menyimpan harga setiap jenis sampah
window.hargaSampah = {

    <?php
    mysqli_data_seek($data, 0);

    while ($r = mysqli_fetch_assoc($data)) {
        echo (int)$r['id_sampah'] . ':'
            . (float)$r['harga_per_kg'] . ',';
    }
    ?>

};

</script>

</main>
</div>
</body>
</html>
