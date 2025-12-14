<?php
header("Content-Type: application/json");
require "config.php";

$nama     = $_POST["nama"]  ?? "";
$email    = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($nama === "" || $email === "" || $password === "") {
  echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (nama, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nama, $email, $hash);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "Registrasi berhasil"]);
} else {
  if ($conn->errno === 1062) {
    echo json_encode(["success" => false, "message" => "Email sudah terdaftar"]);
  } else {
    echo json_encode(["success" => false, "message" => "Error server"]);
  }
}
$stmt->close();
$conn->close();
?>
