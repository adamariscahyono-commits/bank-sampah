<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Bank Sampah</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f8faf9;
        }

        .login-container {
            max-width: 420px;
        }

        .logo {
            width: 65px;
            height: 65px;
            background: #198754;
            color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: auto;
        }

        .form-control {
            border: 0;
            border-bottom: 2px solid #dee2e6;
            border-radius: 0;
            padding: 12px 5px;
            background: transparent;
        }

        .form-control:focus {
            border-bottom-color: #198754;
            box-shadow: none;
            background: transparent;
        }

        .btn-login {
            padding: 13px;
            border-radius: 10px;
        }

        .welcome {
            color: #198754;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }

    </style>

</head>


<body>


<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="login-container w-100">


        <!-- LOGO -->

        <div class="text-center mb-4">

            <div class="logo">
                ♻
            </div>

        </div>


        <!-- JUDUL -->

        <div class="text-center mb-5">

            <div class="welcome">
                WELCOME BACK
            </div>

            <h2 class="fw-bold mt-2">
                Bank Sampah
            </h2>

            <p class="text-muted">
                Masuk untuk mengelola akunmu.
            </p>

        </div>


        <form action="proses_login.php" method="POST">



            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                >

            </div>



            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

            </div>



            <button
                type="submit"
                class="btn btn-success btn-login w-100"
            >
                Masuk →
            </button>


        </form>


        <!-- FOOTER -->

        <div class="text-center mt-4">

            <small class="text-muted">
                ♻️ Kelola sampah dengan lebih bijak.
            </small>

        </div>


    </div>

</div>


</body>

</html>