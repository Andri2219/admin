<?php
require "functions.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
        
    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .row.content {height: auto;} 
    }

    .chart-container {
      width: 100%;
      margin-top: 20px;
    }
    .button-group {
      margin-top: 20px;
    }

    h1,h4{
      text-align : center;
    }
    .bg-primary {
            background-color: #007bff !important;
        }
        .bg-success {
            background-color: #28a745 !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
        }
        .bg-danger {
            background-color: #dc3545 !important;
        }
        .text-white {
            color: #ffffff !important;
        }
        .text-dark {
            color: #343a40 !important;
        }
  </style>
</head>
<body>

<div class="container">
  <div class="button-group">
    <button class="btn btn-primary" onclick="showChart('line')">Line Chart</button>
    <button class="btn btn-success" onclick="showChart('bar')">Bar Chart</button>
    <button class="btn btn-info" onclick="showChart('pie')">Pie Chart</button>
  </div>
  <div class="chart-container">
    <canvas id="myChart"></canvas>
  </div>
</div>

<script>
  let chart;
  const ctx = document.getElementById('myChart').getContext('2d');

  function showChart(type) {
    const data = {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [{
        label: 'Sample Data',
        data: [65, 59, 80, 81, 56, 55, 40],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    };

    const config = {
      type: type,
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };

    if (chart) {
      chart.destroy();
    }

    chart = new Chart(ctx, config);
  }

  // Initialize with a default chart
  showChart('line');
</script>

<br><br>
<div class="row">
        <div class="col-sm-8">
          <div class="well">
            <p>Text...</p> 
          </div>
        </div>
        <div class="col-sm-4">
          <div class="well">
            <p>Text...</p> 
          </div>
        </div>
      </div>

<a href="lihat.php">
<div class="row">
    <div class="col-sm-3">
        <div class="well bg-primary text-white">
            <h4>Total Mahasiswa</h4>
            <?php
            // Query untuk menghitung jumlah mahasiswa
            $sql = "SELECT COUNT(*) as total_mahasiswa FROM mahasiswa";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mengambil hasil query
                $row = $result->fetch_assoc();
                echo "<h1>".$row['total_mahasiswa']."</h1>";
            } else {
                echo "Tidak ada data mahasiswa.";
            }
            ?> 
        </div></a>
    </div>
    <div class="col-sm-3">
        <div class="well bg-success text-white">
            <h4>Mahasiswa Aktif</h4>
            <?php
            // Query untuk menghitung jumlah mahasiswa dengan status aktif menggunakan IF
            $sql = "SELECT 
            COUNT(IF(status.id_status = 'A', 1, NULL)) as total_aktif
            FROM mahasiswa
            JOIN status ON mahasiswa.id_status = status.id_status";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mengambil hasil query
                $row = $result->fetch_assoc();
                echo "<h1>". $row["total_aktif"]."</h1>";
            } else {
                echo "Tidak ada data mahasiswa.";
            }
            ?> 
        </div>
    </div>
    <div class="col-sm-3">
        <div class="well bg-warning text-dark">
            <h4>Mahasiswa Non Aktif</h4>
            <?php
            // Query untuk menghitung jumlah mahasiswa dengan status aktif menggunakan IF
            $sql = "SELECT 
            COUNT(IF(status.id_status = 'TA', 1, NULL)) as total_Taktif
            FROM mahasiswa
            JOIN status ON mahasiswa.id_status = status.id_status";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mengambil hasil query
                $row = $result->fetch_assoc();
                echo "<h1>". $row["total_Taktif"]."</h1>";
            } else {
                echo "Tidak ada data mahasiswa.";
            }
            ?>  
        </div>
    </div>
    <div class="col-sm-3">
        <div class="well bg-danger text-white">
            <h4>ADMIN</h4>
            <?php
            // Query untuk menghitung jumlah user yang sudah registrasi
            $sql = "SELECT COUNT(*) as total_users FROM user";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mengambil hasil query
                $row = $result->fetch_assoc();
                echo "<h1>".  $row["total_users"]."</h1>";
            } else {
                echo "Tidak ada data user.";
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </div>
    </div>
  </div>
  
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
