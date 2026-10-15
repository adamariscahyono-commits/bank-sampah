
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Penilaian Siswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#eef2f7;
    font-family:'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar{
    background:linear-gradient(90deg,#0f172a,#1e40af);
    box-shadow:0 5px 15px rgba(0,0,0,.3);
}
.navbar-brand{
    color:white !important;
    font-size:25px;
    font-weight:bold;
}
.nav-link{
    color:#dbeafe !important;
    font-weight:500;
    margin-right:10px;
}
.nav-link:hover{
    color:white !important;
}

/* BUTTON MENU */
.btn-search{
    background:#2563eb;
    color:white;
    border-radius:30px;
    border:none;
}
.btn-search:hover{
    background:#1d4ed8;
    color:white;
}

/* CONTENT */
.content{
    background:white;
    margin-top:35px;
    padding:30px;
    border-radius:20px;
    box-shadow: 0 10px 25px rgba(0,0,0,.12);
}

/* CARD */
.card{
    border:none;
    border-radius:20px;
    box-shadow: 0 5px 15px rgba(0,0,0,.1);
}

/* TABLE */
.table{
    border-radius:15px;
    overflow:hidden;
}
.table thead{
    background:#1e40af;
    color:white;
}
.table tbody tr:hover{
    background:#dbeafe;
}

/* INPUT */
.form-control{
    border-radius:10px;
}
.form-control:focus{
    border-color:#2563eb;
    box-shadow:0 0 8px rgba(37,99,235,.3);
}

/* DROPDOWN */
.dropdown-menu{
    border-radius:15px;
}

footer{
    text-align:center;
    margin-top:30px;
    color:#64748b;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-mortarboard-fill"></i>
            Sistem Penilaian
        </a>

        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="bi bi-house-fill"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=siswa/index"><i class="bi bi-people-fill"></i> Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=guru/index"><i class="bi bi-person-badge-fill"></i> Guru</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=mapel/index"><i class="bi bi-book-fill"></i> Mapel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=nilai/index"><i class="bi bi-clipboard-data-fill"></i> Nilai</a>
                </li>
            </ul>
            
          <select class="form-select" style="width: 150px;" onchange="if(this.value) window.location.href=this.value;">
               <option value="">Cari Menu</option>
               <option value="?page=home">Home</option>
               <option value="?page=siswa/index">Siswa</option>
               <option value="?page=guru/index">Guru</option>
               <option value="?page=mapel/index">Mapel</option>
               <option value="?page=nilai/index">Nilai</option>
            </select>

            <!-- Tombol Logout -->
            <a href="logout.php" class="btn btn-danger ms-lg-3 mt-3 mt-lg-0" onclick="return confirm('Apakah kamu yakin ingin keluar?');">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            
        </div>
    </div>
</nav>


<!-- CONTENT -->
<div class="container">
    <div class="content">
        <?php
        if(isset($_GET['page'])){
            include $_GET['page'].".php";
        }else{
            include "home.php";
        }
        ?>
    </div>
    <footer>
        © 2026 Sistem Penilaian Siswa
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>