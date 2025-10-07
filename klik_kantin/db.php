<?php
error_reporting(E_ALL); // Tampilkan semua error
ini_set('display_errors', 1); // Tampilkan error di browser

$host = "localhost";
$user = "root";
$pass = "";
$db = "klik_kantin";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>