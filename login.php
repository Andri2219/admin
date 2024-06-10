<?php
session_start();
require "functions.php";

//cek cookie
if(isset($_COOKIE["id"]) && isset($_COOKIE["key"])){
    $id = $_COOKIE["id"];
    $key = $_COOKIE["key"];


    //ambil username berdasarkan id
    $result = mysqli_query( $conn, "SELECT username FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    //cek cookie dan username
    if ( $key === hash('sha256', $row['username']) ){
        $_SESSION['login'] =true;
    }
}


if(isset($_SESSION["login"])){
    header("Location: dashboard.php");
    exit;
}



if(isset($_POST["login"])){

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query( $conn, "SELECT * FROM user WHERE username = '$username'");


    //cek username
    if(mysqli_num_rows($result) === 1){

        //cek password
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row["password"])){
            //set sessinon
            $_SESSION['login']=true;

            //cek remember me
            if(isset($_POST['remember'])){
                //buat cookie
                setcookie('id', $row['id'], time()+60);
                setcookie('key', hash('sha256', $row['username']), time()+60);
            }

            header("Location: dashboard.php");
            exit;
        }
    }


    $error = true;
 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Login</h1>

            <?php if(isset($error)) :?>
                <p class="error-message">username / password salah</p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-group remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <p>Belum punya Akun? <a href="registrasi.php">Registrasi</a></p>
                <div class="input-group">
                    <button type="submit" name="login" class="login-button">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
