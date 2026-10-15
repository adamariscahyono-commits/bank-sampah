<?php

include __DIR__ . "/../koneksi.php";

if (!isset($_GET['id_mapel']) || empty($_GET['id_mapel'])) {

    header("Location: index.php?page=mapel/index");
    exit;

}

$id_mapel = $_GET['id_mapel'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM mapel WHERE id_mapel='$id_mapel'"
);

$data = mysqli_fetch_array($query);

if (!$data) {

    header("Location: index.php?page=mapel/index");
    exit;

}

?>
<style>

body{
    background:linear-gradient(135deg,#e0e7ff,#f8fafc);
}


/* CARD */

.mapel-card{
    max-width:700px;
    margin:40px auto;
    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.15);
}


/* HEADER */

.mapel-header{
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:white;
    padding:35px;
    text-align:center;
}


.form-area{
    padding:35px;
}


.form-label{
    font-weight:700;
    color:#334155;
}


.form-control{
    border-radius:15px;
    padding:12px;
    border:2px solid #e2e8f0;
}


.form-control:focus{
    border-color:#2563eb;
    box-shadow:0 0 10px rgba(37,99,235,.3);
}


/* BUTTON */

.btn-update{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    border:none;
    border-radius:30px;
    padding:12px 35px;
    font-weight:bold;
}


.btn-update:hover{
    background:#1e40af;
    color:white;
}


.btn-back{
    background:#64748b;
    color:white;
    border-radius:30px;
    padding:12px 30px;
}


.btn-back:hover{
    background:#475569;
    color:white;
}

</style>


<div class="mapel-card">

    <div class="mapel-header">

        <div class="logo-mapel">
            <i class="bi bi-book-half"></i>
        </div>

        <h2 class="mt-3">
            Edit Mata Pelajaran
        </h2>

        <p>
            Sistem Informasi Sekolah Adam Aris
        </p>

    </div>


    <div class="form-area">

        <form action="?page=mapel/update" method="POST">

            <!-- ID untuk dikirim ke update.php -->
            <input
                type="hidden"
                name="id_mapel"
                value="<?= htmlspecialchars($data['id_mapel']); ?>"
            >


            <div class="mb-4">

                <label class="form-label">
                    <i class="bi bi-journal-text text-primary"></i>
                    ID Mapel
                </label>

                <input
                    type="text"
                    class="form-control bg-light"
                    value="<?= htmlspecialchars($data['id_mapel']); ?>"
                    readonly
                >

            </div>


            <div class="mb-4">

                <label class="form-label">
                    <i class="bi bi-journal-text text-primary"></i>
                    Nama Mapel
                </label>

                <input
                    type="text"
                    name="nama_mapel"
                    class="form-control"
                    value="<?= htmlspecialchars($data['nama_mapel']); ?>"
                    required
                >

            </div>


            <div class="mb-4">

                <label class="form-label">
                    <i class="bi bi-person-workspace text-primary"></i>
                    Guru Pengajar
                </label>

                <input
                    type="text"
                    name="guru"
                    class="form-control"
                    value="<?= htmlspecialchars($data['guru']); ?>"
                    required
                >

            </div>


            <div class="text-center">

                <button
                    type="submit"
                    class="btn btn-update me-2"
                >
                    <i class="bi bi-save-fill"></i>
                    Update Data
                </button>


                <a
                    href="?page=mapel/index"
                    class="btn btn-back"
                >
                    <i class="bi bi-arrow-left-circle"></i>
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>