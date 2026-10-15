<?php
session_start();
require_once "../config/koneksi.php";

$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($query)==1){

    $user=mysqli_fetch_assoc($query);

    if($password==$user["password"]){

        $_SESSION["login"]=true;
        $_SESSION["id_user"]=$user["id_user"];
        $_SESSION["nama"]=$user["nama"];
        $_SESSION["role"]=$user["role"];

       if ($user["role"] == "admin") {
    header("Location: ../admin/index.php");
} elseif ($user["role"] == "pengepul") {
    header("Location: ../pengepul/index.php");
} else {
    header("Location: ../user/index.php");
}
exit;
    }

}

echo "<script>
alert('Username atau Password salah');
window.location='login.php';
</script>";