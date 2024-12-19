<?php
$conn = mysqli_connect("localhost", "root", "", "film_rekomendasi");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
