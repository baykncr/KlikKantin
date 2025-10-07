<?php
ob_start(); // Mulai output buffering

session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

$canteen_id = intval($_GET['canteen_id']);
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;

$menu = []; // Inisialisasi $menu
if ($edit_id > 0) {
    $menu = $conn->query("SELECT * FROM menus WHERE id=$edit_id")->fetch_assoc();
}

if (isset($_POST['save'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']); // Gunakan floatval untuk harga
    $photo = $edit_id && isset($menu['photo']) ? $menu['photo'] : null; // Ambil foto lama jika edit

    // Gunakan real_escape_string
    $escaped_name = $conn->real_escape_string($name);

    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $target_file = "../images/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    if ($edit_id > 0) {
        $stmt = $conn->prepare("UPDATE menus SET name=?, price=?, photo=? WHERE id=?");
        $stmt->bind_param("sssi", $escaped_name, $price, $photo, $edit_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO menus (canteen_id, name, price, photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iss", $canteen_id, $escaped_name, $price, $photo);
    }
    $stmt->execute();
    $stmt->close();

    ob_end_clean();
    header("Location: edit_menu.php?canteen_id=$canteen_id&success=menu_saved");
    exit();
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $edit_id ? "Edit" : "Tambah"; ?> Menu</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <div class="card" style="max-width: 500px; margin: 3rem auto; padding: 2rem;">
      <h2 style="margin-bottom: 1.5rem;"><?php echo $edit_id ? "Edit" : "Tambah"; ?> Menu</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Nama Menu</label>
          <input type="text" id="name" name="name" placeholder="Nama Menu" value="<?php echo htmlspecialchars($menu['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label for="price">Harga</label>
          <input type="number" id="price" name="price" placeholder="Harga" value="<?php echo htmlspecialchars($menu['price'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label for="photo">Foto Menu</label>
          <input type="file" id="photo" name="photo">
          <?php if (!empty($menu['photo'])): ?>
            <small style="display: block; margin-top: 0.5rem;">Foto saat ini:</small>
            <img src="../images/<?php echo htmlspecialchars($menu['photo']); ?>" width="100" class="img-thumbnail" style="margin-top: 0.5rem;"><br>
          <?php endif; ?>
        </div>
        <button type="submit" name="save" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Simpan</button>
        <a href="edit_menu.php?canteen_id=<?php echo $canteen_id; ?>" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">Batal</a>
      </form>
    </div>
  </div>
</body>
</html>