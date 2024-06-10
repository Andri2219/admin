<?php
//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "unpku");


function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}


function tambah($data) {
    global $conn;

    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $id_prodi = htmlspecialchars($data["id_prodi"]); 
    $id_status = htmlspecialchars($data["id_status"]); 
   
    //upload gambar
    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    $query = "INSERT INTO mahasiswa (npm, nama, email, id_prodi, id_status, gambar)
              VALUES ('$npm', '$nama', '$email', '$id_prodi', '$id_status','$gambar')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function upload(){
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    //cek apakah tidak ada gambar yang diupload
    if( $error === 4){
        echo "<script>
        alert('pilih gambar terlebih dahulu!');
        </script>";
        return false;
    }

    //cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ){
        echo "<script>
        alert('yang anda upload bukan gambar!');
        </script>";
        return false;
    }

    //cek jika ukurannya terlalu besar
    if( $ukuranFile > 1000000) {
        echo "<script>
        alert('ukuran gambar terlalu besar!');
        </script>";
        return false;
    }

    //lolos pengecekan, gambar siap upload
    //genrate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}




function hapus($npm) {
    global $conn;
    mysqli_query( $conn, "DELETE FROM mahasiswa WHERE npm = $npm");
    return mysqli_affected_rows($conn);
}


function ubah($data) {
    global $conn;

    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $id_prodi = htmlspecialchars($data["id_prodi"]);
    $id_status = htmlspecialchars($data["id_status"]); 
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    //cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    $query = "UPDATE mahasiswa 
              SET nama ='$nama', email = '$email', id_prodi = '$id_prodi',id_status = '$id_status', gambar = '$gambar' 
              WHERE npm = $npm";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}


function cari($keyword) {
    global $conn;
    $query = "SELECT mahasiswa.*, prodi.nama_prodi, status.ket, prodi.kampus
              FROM mahasiswa 
              INNER JOIN prodi ON mahasiswa.id_prodi = prodi.id_prodi
              INNER JOIN status ON mahasiswa.id_status = status.id_status
              WHERE
              mahasiswa.nama LIKE '%$keyword%' OR 
              mahasiswa.npm LIKE '%$keyword%' OR
              mahasiswa.email LIKE '%$keyword%' OR
              prodi.nama_prodi LIKE '%$keyword%' OR
              prodi.kampus LIKE '%$keyword%' OR
              status.ket LIKE '%$keyword%'";
    return query($query);
}



function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        echo "
        <script>
        alert('Username sudah terdaftar!');
        </script>";
        return false;
    }
    $stmt->close();

    // Check password confirmation
    if ($password !== $password2) {
        echo "
        <script>
        alert('Konfirmasi password tidak sesuai!');
        </script>";
        return false;
    }

    // Encrypt password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $passwordHash);
    $stmt->execute();

    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    return $affectedRows;
}





?>