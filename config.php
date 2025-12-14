<?php
$host = "localhost";
$user = "root";      // default XAMPP
$pass = "";          // default kosong
$db   = "tokoku";    // nama database yang barusan kamu buat

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>
