<?php
/* Sidebar bersama untuk admin / pengepul / user.
   Menu otomatis menyesuaikan folder halaman yang memanggilnya. */

$folder = basename(dirname($_SERVER["SCRIPT_NAME"]));
$file   = basename($_SERVER["SCRIPT_NAME"]);

$menus = [
    "admin" => [
        ["index.php",                "bi-speedometer2", "Dashboard"],
        ["persetujuan_setor.php",    "bi-recycle",      "Persetujuan Setor"],
        ["persetujuan_tarik.php",    "bi-cash-coin",    "Persetujuan Tarik"],
        ["persetujuan_checkout.php", "bi-bag-check",    "Persetujuan Checkout"],
        ["riwayat.php",              "bi-clock-history","Riwayat"],
        ["sampah.php",               "bi-box-seam",     "Kelola Sampah"],
    ],
    "pengepul" => [
        ["index.php",    "bi-speedometer2", "Dashboard"],
        ["checkout.php", "bi-cart-check",   "Checkout Sampah"],
        ["riwayat.php",  "bi-receipt",      "Riwayat Checkout"],
    ],
    "user" => [
        ["index.php",   "bi-speedometer2", "Dashboard"],
        ["setor.php",   "bi-recycle",      "Setor Sampah"],
        ["riwayat.php", "bi-clock-history","Riwayat"],
        ["tarik.php",   "bi-wallet2",      "Tarik Saldo"],
    ],
];

// halaman turunan ikut menyalakan menu induknya
$alias = ["edit_sampah.php" => "sampah.php", "struk.php" => "riwayat.php"];
$aktif = $alias[$file] ?? $file;

$menu = $menus[$folder] ?? $menus["user"];
$nama = $_SESSION["nama"] ?? "Pengguna";
$role = $_SESSION["role"] ?? $folder;
$inisial = strtoupper(mb_substr($nama, 0, 1));

$labelRole = ["admin" => "Admin", "pengepul" => "Pengepul", "user" => "Nasabah"][$folder] ?? "User";
?>
<button class="menu-btn" type="button" aria-label="Menu"
        onclick="document.body.classList.toggle('nav-open')">
    <i class="bi bi-list"></i>
</button>
<div class="scrim" onclick="document.body.classList.remove('nav-open')"></div>

<aside class="sidebar">

    <div class="brand">
        <div class="brand-logo">🌱</div>
        <div>
            <div class="brand-name">EcoBank</div>
            <span class="brand-role"><?= $labelRole ?></span>
        </div>
    </div>

    <nav class="nav">
        <?php foreach ($menu as [$href, $ikon, $teks]) { ?>
            <a href="<?= $href ?>" class="<?= $aktif === $href ? "active" : "" ?>">
                <i class="bi <?= $ikon ?>"></i> <?= $teks ?>
            </a>
        <?php } ?>
    </nav>

    <div class="side-user">
        <div class="avatar"><?= htmlspecialchars($inisial) ?></div>
        <div class="who">
            <b><?= htmlspecialchars($nama) ?></b>
            <small><?= htmlspecialchars($role) ?></small>
        </div>
        <a class="logout" href="../auth/logout.php" title="Logout"
           onclick="return confirm('Keluar dari akun?')">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>

</aside>
