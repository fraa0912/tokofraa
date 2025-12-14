<?php
header('Content-Type: application/json');

require 'config.php'; // pakai koneksi yang sudah ada

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
  exit;
}

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Gagal koneksi DB']);
  exit;
}

// ambil data dari POST
$nama_penerima = $_POST['nama_penerima'] ?? '';
$no_wa         = $_POST['no_wa'] ?? '';
$alamat        = $_POST['alamat'] ?? '';
$kota          = $_POST['kota'] ?? '';
$kode_pos      = $_POST['kode_pos'] ?? '';
$catatan       = $_POST['catatan'] ?? '';
$metode_bayar  = $_POST['metode_bayar'] ?? '';
$total         = $_POST['total'] ?? 0;

// ambil user_id dari session lokal (optional, pakai 0 dulu)
$user_id = 0;

// validasi sangat simpel
if ($nama_penerima === '' || $no_wa === '' || $alamat === '' || $kota === '' || $kode_pos === '') {
  echo json_encode(['success' => false, 'message' => 'Data belum lengkap']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO orders 
  (user_id, nama_penerima, no_wa, alamat, kota, kode_pos, catatan, metode_bayar, total)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
  "isssssssi",
  $user_id, $nama_penerima, $no_wa, $alamat, $kota, $kode_pos, $catatan, $metode_bayar, $total
);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message' => 'Pesanan tersimpan', 'order_id' => $stmt->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Gagal simpan pesanan']);
}

$stmt->close();
$conn->close();
