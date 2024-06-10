<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}

require "functions.php";
// Batasan jumlah data per halaman
$limit = 3;

// Hitung total data
$totalRows = count(query("SELECT * FROM mahasiswa"));

// Hitung total halaman
$totalPages = ceil($totalRows / $limit);

// Tentukan halaman saat ini
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Hitung offset untuk query database
$offset = ($current_page - 1) * $limit;

// Query untuk menggabungkan tabel mahasiswa dan prodi serta menampilkan kolom yang diperlukan
$mahasiswa = query("SELECT mahasiswa.*, status.ket, prodi.nama_prodi, prodi.kampus 
                      FROM mahasiswa 
                      INNER JOIN prodi ON mahasiswa.id_prodi = prodi.id_prodi
                      INNER JOIN status ON mahasiswa.id_status = status.id_status");

//tombol cari ditekan
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
</head>

<body>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-6">
        <form action="" method="post" class="form-inline mb-4" id="searchForm">
            <input type="text" name="keyword" id="keyword" size="40" class="form-control mr-2" autofocus placeholder="Masukkan keyword pencarian..." autocomplete="off">
        </form>
    </div>
    <div class="col-md-6 text-right">
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end mb-0">
                <!-- Tombol "Previous" -->
                <li class="page-item <?php echo ($current_page == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <!-- Tombol-tombol halaman -->
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <!-- Tombol "Next" -->
                <li class="page-item <?php echo ($current_page == $totalPages || $totalPages == 0) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th scope="col">No.</th>
        <th scope="col">Image</th>
        <th scope="col">Student Details</th>
        <th scope="col">Campus</th> 
      </tr>
    </thead>
    <tbody>
      <?php $i = 1 + $offset; ?>
      <?php $end = min($offset + $limit, $totalRows); ?>
      <?php for ($i; $i <= $end; $i++) : ?>
      <tr>
        <td><?= $i; ?></td>
        <td><img src="img/<?= $mahasiswa[$i - 1]["gambar"]; ?>" class="img-thumbnail" width="110"></td>
        <td>
          <strong>NPM:</strong> <?= $mahasiswa[$i - 1]["npm"]; ?><br>
          <strong>Nama:</strong> <?= $mahasiswa[$i - 1]["nama"]; ?><br>
          <strong>Email:</strong> <?= $mahasiswa[$i - 1]["email"]; ?><br>
          <strong>Prodi:</strong> <?= $mahasiswa[$i - 1]["nama_prodi"]; ?><br>
          <strong>Status:</strong> <?= $mahasiswa[$i - 1]["ket"]; ?>
        </td>
        <td><?= $mahasiswa[$i - 1]["kampus"]; ?></td> 
      </tr>
      <?php endfor ?>
    </tbody>
  </table>
</div>

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
                    url: 'searchli.php',
                    method: 'POST',
                    data: {keyword: keyword, page: 1}, // Mengatur halaman kembali ke 1 saat input keyword tidak kosong
                    success: function(data){
                        $('tbody').html(data);
                    }
                });
            } else { // Jika input keyword kosong
                // Mengembalikan tampilan tabel ke halaman 1 dengan data default
                window.location.href = '?page=1';
            }
        });

        // Handling pagination click events
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var keyword = $('#keyword').val().trim();

            if (keyword !== '') {
                $.ajax({
                    url: 'searchli.php',
                    method: 'POST',
                    data: {keyword: keyword, page: page},
                    success: function(data) {
                        $('tbody').html(data);
                    }
                });
            } else {
                window.location.href = '?page=' + page;
            }
        });
    });
</script>
    </body>
</html>
