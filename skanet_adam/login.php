
<?php
session_start();
include "koneksi.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users WHERE username='$username' AND password='$password'"
    );

    $cek = mysqli_num_rows($query);

    if ($cek > 0) {

        // Berhasil login
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;

    } else {

        $error = "Username atau password salah. Silakan coba lagi.";

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Sistem Penilaian</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<style>
    :root{
        --navy-dark:#141b4d;
        --navy:#1b2a6b;
        --blue:#2f5bbf;
        --blue-light:#4f7fe0;
    }
    body{
        min-height:100vh;
        margin:0;
        font-family:'Inter', sans-serif;
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy) 45%, var(--blue) 100%);
        display:flex;
        align-items:center;
        justify-content:center;
        padding:24px;
    }
    .brand-title{
        font-family:'Poppins', sans-serif;
    }
    .login-card{
        width:100%;
        max-width:420px;
        background:#fff;
        border-radius:24px;
        box-shadow: 0 25px 50px -12px rgba(20, 27, 77, 0.45);
        padding:40px 36px;
    }
    .badge-icon{
        width:64px;
        height:64px;
        border-radius:50%;
        background: linear-gradient(135deg, var(--navy), var(--blue-light));
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:28px;
        color:#fff;
        margin:0 auto 16px;
        box-shadow: 0 10px 20px -6px rgba(47, 91, 191, 0.55);
    }
    .form-control{
        border-radius:14px;
        padding:12px 16px;
        border:1px solid #e2e6f0;
        background:#f6f8fc;
    }
    .form-control:focus{
        border-color: var(--blue-light);
        box-shadow: 0 0 0 0.2rem rgba(79, 127, 224, 0.2);
        background:#fff;
    }
    .input-icon{
        position:relative;
    }
    .input-icon i{
        position:absolute;
        top:50%;
        left:16px;
        transform:translateY(-50%);
        color:#8b93ad;
    }
    .input-icon input{
        padding-left:44px;
    }
    .btn-login{
        border-radius:999px;
        padding:12px;
        font-weight:600;
        background: linear-gradient(135deg, var(--navy), var(--blue));
        border:none;
        color:#fff;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-login:hover{
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -8px rgba(47, 91, 191, 0.6);
        color:#fff;
    }
    .footer-text a{
        color: var(--blue);
        text-decoration:none;
        font-weight:500;
    }
    .footer-text a:hover{
        text-decoration:underline;
    }
</style>
</head>
<body>

<div class="login-card">
    <div class="text-center">
        <div class="badge-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <h4 class="brand-title fw-bold mb-0" style="color:var(--navy-dark);">Sistem Penilaian</h4>
        <p class="text-secondary small mb-4">Aplikasi Adam Aris &mdash; masuk untuk mengelola data sekolah</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label small fw-semibold text-secondary">Username</label>
            <div class="input-icon">
                <i class="bi bi-person-fill"></i>
                <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label small fw-semibold text-secondary">Password</label>
            <div class="input-icon">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
            </div>
        </div>
        <button type="submit" name="login" class="btn btn-login w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </button>
    </form>

    <p class="text-center footer-text small text-secondary mt-4 mb-0">
        &copy; <?= date('Y') ?> Sistem Penilaian Siswa
    </p>
</div>

</body>
</html>