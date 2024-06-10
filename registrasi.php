<?php
require "functions.php";

if(isset($_POST["register"])){
    if(registrasi($_POST) > 0){
       echo "
        <script>
        alert('user baru berhasil ditambahkan!');
        document.location.href = 'login.php';
        </script>
        ";
    }else{
        echo mysqli_error($conn);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="registration-container">
        <div class="registration-box">
            <h1>Registrasi</h1>
            <form action="" method="post">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-group">
                    <label for="password2">Konfirmasi Password:</label>
                    <input type="password" name="password2" id="password2" required>
                </div>
                <div class="input-group">
                    <button type="submit" name="register" class="register-button">Register!</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
