<?php
ob_start(); // Mulai output buffering

session_start();
include '../db.php';

// 🔒 Cek apakah login & role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

// Ambil parameter dari URL
if (!isset($_GET['id']) || !isset($_GET['canteen_id']) || empty($_GET['id']) || empty($_GET['canteen_id'])) {
    $canteen_id_for_redirect = isset($_GET['canteen_id']) ? intval($_GET['canteen_id']) : 0;
    ob_end_clean();
    header("Location: edit_menu.php?canteen_id=" . $canteen_id_for_redirect . "&error=param_missing");
    exit();
}

$id = intval($_GET['id']); // ID menu yang akan dihapus
$canteen_id = intval($_GET['canteen_id']); // ID kantin pemilik menu

// Cek apakah menu benar-benar ada dan milik kantin yang benar
$check_stmt = $conn->prepare("SELECT id FROM menus WHERE id = ? AND canteen_id = ?");
$check_stmt->bind_param("ii", $id, $canteen_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows === 0) {
    $check_stmt->close();
    ob_end_clean();
    header("Location: edit_menu.php?canteen_id=$canteen_id&error=menu_not_found");
    exit();
}
$check_stmt->close();

// Lakukan penghapusan menu menggunakan prepared statement
$delete_stmt = $conn->prepare("DELETE FROM menus WHERE id = ? AND canteen_id = ?");
$delete_stmt->bind_param("ii", $id, $canteen_id);

if ($delete_stmt->execute()) {
    $delete_stmt->close();
    ob_end_clean();
    header("Location: edit_menu.php?canteen_id=$canteen_id&success=menu_deleted");
} else {
    $delete_stmt->close();
    ob_end_clean();
    header("Location: edit_menu.php?canteen_id=$canteen_id&error=delete_failed");
}

exit();
?>