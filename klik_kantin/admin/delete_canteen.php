<?php
ob_start(); // Mulai output buffering

session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    ob_end_clean();
    header("Location: dashboard.php?error=param_missing");
    exit();
}

$id = intval($_GET['id']);

// Hapus rating terkait
$stmt_ratings = $conn->prepare("DELETE FROM ratings WHERE canteen_id = ?");
$stmt_ratings->bind_param("i", $id);
$stmt_ratings->execute();
$stmt_ratings->close();

// Hapus menu terkait
$stmt_menus = $conn->prepare("DELETE FROM menus WHERE canteen_id = ?");
$stmt_menus->bind_param("i", $id);
$stmt_menus->execute();
$stmt_menus->close();

// Hapus kantin
$stmt_canteen = $conn->prepare("DELETE FROM canteens WHERE id = ?");
$stmt_canteen->bind_param("i", $id);

if ($stmt_canteen->execute()) {
    $stmt_canteen->close();
    ob_end_clean();
    header("Location: dashboard.php?success=canteen_deleted");
} else {
    $stmt_canteen->close();
    ob_end_clean();
    header("Location: dashboard.php?error=delete_failed");
}

exit();
?>