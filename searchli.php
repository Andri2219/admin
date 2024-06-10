<?php
require "functions.php";

if (isset($_POST["keyword"])) {
    $keyword = trim($_POST["keyword"]); 
    $mahasiswa = cari($keyword);

    // Check if results found
    if ($mahasiswa) {
        $i = 1;
        foreach ($mahasiswa as $row) {
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td><img src='img/{$row['gambar']}' class='img-thumbnail' width='50'></td>";
            echo "<td>";
            echo "<strong>NPM:</strong> {$row['npm']}<br>";
            echo "<strong>Nama:</strong> {$row['nama']}<br>";
            echo "<strong>Email:</strong> {$row['email']}<br>";
            echo "<strong>Prodi:</strong> {$row['nama_prodi']}<br>";
            echo "<strong>Status:</strong> {$row['ket']}";
            echo "</td>";
            echo "<td>{$row['kampus']}</td>";
            echo "</tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='7'>No results found for the keyword '$keyword'.</td></tr>";
    }
}
?>
