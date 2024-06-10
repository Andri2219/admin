<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}


require 'functions.php';

//ambil data di URL
$npm = $_GET["npm"];
//query data mahasiswa berdasarkan id
$mhs = query("SELECT * FROM mahasiswa WHERE npm = $npm")[0];

//cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"] ) ) {
    

    //cek apakah data berhasil diubah atau tidak
    if(ubah($_POST) > 0){
        echo "
        <script>
        alert('data berhasil diubah!');
        document.location.href = 'index.php';
        </script>
        ";
    }else{
        echo "
        <script>
        alert('data gagal diubah!');
        document.location.href = 'index.php';
        </script>
        ";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah data mahasiswa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .form-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: green;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            width: 100%;
            margin-bottom: 5px;
        }

        .input-group select{
            width: 100%;
            height: 20%;
            padding: 10px;
            box-sizing: border-box; 
        }

        .input-group input{
            width: 100%;
            padding: 10px;
            height: 20%;
            box-sizing: border-box;
        }

        /* Grid layout for form */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-col {
            flex-basis: calc(50% - 10px);
        }

        /* Styling for title */
        .title-container {
            background-color: #337ab7;
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 10px 20px;
            margin-bottom: 20px;
        }

        .title {
            margin: 0;
            font-size: 24px;
        }
    </style>
</head>
<body>
   <div class="container">
       <div class="form-container">
           <div class="title-container">
               <h2 class="title">Ubah Data Mahasiswa</h2>
           </div>

           <form action="" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-col">
                        <div class="input-group">
                            <label for="npm">NPM:</label>
                            <input type="text" name="npm" id="npm" readonly value="<?= $mhs["npm"]; ?>">
                        </div>
                        <div class="input-group">
                            <label for="nama">Nama:</label>
                            <input type="text" name="nama" id="nama" required value="<?= $mhs["nama"]; ?>">
                        </div>
                        <div class="input-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" required value="<?= $mhs["email"]; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="input-group">
                            <label for="id_prodi">Program Studi:</label>
                            <select name="id_prodi" id="id_prodi" class="form-control" required>
                                <option value="">----</option>
                                <option value="TI" <?php if($mhs["id_prodi"] == "TI") echo "selected"; ?>>Teknik Informatika</option>
                                <option value="SI" <?php if($mhs["id_prodi"] == "SI") echo "selected"; ?>>Sistem Informasi</option>
                                <option value="TM" <?php if($mhs["id_prodi"] == "TM") echo "selected"; ?>>Teknik Mesin</option>
                                <option value="TE" <?php if($mhs["id_prodi"] == "TE") echo "selected"; ?>>Teknik Elektro</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="id_status">Status Mahasiswa:</label>
                            <select name="id_status" id="id_status" class="form-control" required>
                                <option value="">----</option>
                                <option value="A" <?php if($mhs["id_status"] == "A") echo "selected"; ?>>AKTIF</option>
                                <option value="TA" <?php if($mhs["id_status"] == "TA") echo "selected"; ?>>TIDAK AKTIF</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="gambar">Image:</label><br>
                            <img src="img/<?= $mhs['gambar']; ?>" width="40"><br>
                            <input type="file" name="gambar" id="gambar">
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <button type="submit" name="submit" class="btn btn-primary submit-button">Ubah Data</button>
                </div>
           </form>
       </div>
   </div>
</body>
</html>
