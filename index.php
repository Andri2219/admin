<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}

require "functions.php";

// Batasan jumlah data per halaman
$limit = 5;

// Hitung total data
$totalRows = count(query("SELECT * FROM mahasiswa"));

// Hitung total halaman
$totalPages = ceil($totalRows / $limit);

// Tentukan halaman saat ini
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Hitung offset untuk query database
$offset = ($current_page - 1) * $limit;

// Query untuk mengambil data dengan batasan
$mahasiswa = query("SELECT mahasiswa.*, prodi.nama_prodi, status.ket 
                      FROM mahasiswa 
                      INNER JOIN prodi ON mahasiswa.id_prodi = prodi.id_prodi
                      INNER JOIN status ON mahasiswa.id_status = status.id_status
                      LIMIT $limit OFFSET $offset");

// Tombol cari ditekan
if(isset($_POST["cari"])){
  $mahasiswa = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        /* Full width and height iframe for content display */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

      
        /* Style untuk tabel */
        table {
            width: 100%;
            border-collapse: collapse; /* Menggabungkan border-cells */
            background-color: rgba(255, 255, 255, 0.8); /* Warna latar belakang tabel (transparan) */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; /* Mengatur jenis teks di dalam tabel */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Garis bawah untuk setiap baris */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; /* Mengatur jenis teks di dalam tabel */
        }

        th {
            background-color: #337ab7;
            color: #fff;
        }


        /* Tombol aksi */
        .btn-action {
            margin-right: 5px;
        }

        /* Gambar thumbnail */
        .img-thumbnail {
            max-width: 50px;
            height: auto;
        }
    </style>
</head>

<body>
    <br>
    <div class="col-md-6">
        <form action="" method="post" class="form-inline mb-4" id="searchForm">
            <input type="text" name="keyword" id="keyword" size="40" class="form-control mr-2" autofocus placeholder="Masukkan keyword pencarian..." autocomplete="off">
        </form>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">NPM</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Prodi</th> 
                <th scope="col">Status</th> 
                <th scope="col">Update</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $row) : ?>
                <tr>
                    <td><img src="img/<?= $row["gambar"]; ?>" class="img-thumbnail"></td>
                    <td><?= $row["npm"]; ?></td>
                    <td><?= $row["nama"]; ?></td>
                    <td><?= $row["email"]; ?></td>
                    <td><?= $row["nama_prodi"]; ?></td>
                    <td><?= $row["ket"]; ?></td>
                    <td>
                        <a href="ubah.php?npm=<?= $row["npm"]; ?>" class="btn btn-warning btn-sm btn-action"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg></a>
                    </td>
                    <td>
                        <a href="hapus.php?npm=<?= $row["npm"]; ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Yakin?');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
</svg></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <!-- Tombol "Previous" -->
            <li class="page-item <?php echo ($current_page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Tombol-tombol halaman -->
            <?php for($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Tombol "Next" -->
            <li class="page-item <?php echo ($current_page == $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
           

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#keyword').on('input', function(){
                var keyword = $(this).val().trim(); // Menghapus spasi di awal dan akhir

                // Jika input keyword tidak kosong
                if (keyword !== '') {
                    $.ajax({
                        url: 'search.php',
                        method: 'POST',
                        data: {keyword: keyword, page: 1}, // Mengatur halaman kembali ke 1 saat input keyword tidak kosong
                        success: function(data){
                            $('tbody').html(data);
                        }
                    });
                } else { // Jika input keyword kosong
                    // Mengembalikan tampilan tabel ke halaman 1 dengan 4 baris data pertama
                    $.ajax({
                        url: 'search.php',
                        method: 'POST',
                        data: {page: 1, limit: 4}, // Mengatur halaman kembali ke 1 dengan 4 baris data pertama
                        success: function(data){
                            $('tbody').html(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

