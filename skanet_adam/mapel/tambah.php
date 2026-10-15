<div class="container mt-4">

    <div class="card shadow border-0 rounded-4">

        <div class="card-header text-white"
             style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">

            <h3 class="mb-0">
                <i class="bi bi-book-fill"></i>
                Tambah Data Mapel
            </h3>

        </div>

        <div class="card-body">

            <form action="?page=mapel/simpan" method="POST">

            <div class="mb-3">
                    <label class="form-label">Id</label>

                    <input type="text"
                           name="id_mapel"
                           class="form-control"
                           placeholder="Contoh: n 11"
                           required>
                </div>


                <div class="mb-3">
                    <label class="form-label">Nama Mapel</label>

                    <input type="text"
                           name="nama_mapel"
                           class="form-control"
                           placeholder="Masukkan nama mapel"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Guru</label>

                    <input type="text"
                           name="guru"
                           class="form-control"
                           placeholder="Masukkan nama guru"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="?page=mapel/index" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</div>