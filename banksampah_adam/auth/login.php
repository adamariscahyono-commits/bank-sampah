<?php
session_start();

if(isset($_SESSION["login"])){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<?php $judul = "Login"; require __DIR__ . "/../partials/head.php"; ?>
</head>
<body class="auth-body">

<div class="orb o1"></div>
<div class="orb o2"></div>
<div class="orb o3"></div>

<div class="auth-card">

    <div class="auth-logo">🌱</div>

    <h1 class="grad-text">EcoBank</h1>
    <p class="sub">Masuk dulu, yuk. Kelola sampah jadi lebih cerdas ♻</p>

    <form action="cek_login.php" method="POST">

        <div class="field">
            <label>Username</label>
            <div class="input-icon">
                <i class="bi bi-person-fill lead"></i>
                <input type="text" name="username" autocomplete="off"
                       placeholder="Masukkan username" required>
            </div>
        </div>

        <div class="field">
            <label>Password</label>
            <div class="input-icon">
                <i class="bi bi-lock-fill lead"></i>
                <input type="password" name="password" id="password"
                       autocomplete="new-password" placeholder="Masukkan password" required>
                <button class="eye" type="button" onclick="lihatPassword()" aria-label="Lihat password">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <button class="btn btn-block" type="submit" style="margin-top:6px">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>

    </form>

    <div class="chips">
        <span>Admin</span><span>Pengepul</span><span>Nasabah</span>
    </div>

    <div class="auth-foot">Kelola sampah, kumpulkan saldo ✨</div>

</div>

<script>
function lihatPassword(){
    let pass = document.getElementById("password");
    let icon = document.getElementById("eyeIcon");
    let show = pass.type === "password";
    pass.type = show ? "text" : "password";
    icon.className = show ? "bi bi-eye-slash" : "bi bi-eye";
}
</script>

</body>
</html>
