<?php
header("Content-Type: application/json");
require "config.php";

$email    = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
  echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
  exit;
}

$stmt = $conn->prepare("SELECT id, nama, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  if (password_verify($password, $row["password_hash"])) {
    echo json_encode([
      "success" => true,
      "message" => "Login berhasil",
      "user"    => ["id" => $row["id"], "nama" => $row["nama"], "email" => $email]
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Password salah"]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Email tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
