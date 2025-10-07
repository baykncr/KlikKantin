// delete_canteen.php
<?php
include '../db.php';
$id = intval($_GET['id']);
$conn->query("DELETE FROM canteens WHERE id=$id");
header("Location: dashboard.php");
?>

// delete_menu.php
<?php
include '../db.php';
$id = intval($_GET['id']);
$canteen_id = intval($_GET['canteen_id']);
$conn->query("DELETE FROM menus WHERE id=$id");
header("Location: edit_menu.php?canteen_id=$canteen_id");
?>
