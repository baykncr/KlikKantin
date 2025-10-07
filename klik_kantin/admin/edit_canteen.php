<?php
ob_start(); // Mulai output buffering

session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$canteen = $conn->query("SELECT * FROM canteens WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $photo = $canteen['photo']; // Default ke foto lama

    // Gunakan real_escape_string
    $escaped_name = $conn->real_escape_string($name);

    // Upload foto baru jika ada
    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $target_file = "../images/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE canteens SET name=?, photo=? WHERE id=?");
    $stmt->bind_param("ssi", $escaped_name, $photo, $id);
    $stmt->execute();
    $stmt->close();

    ob_end_clean();
    header("Location: dashboard.php");
    exit();
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Kantin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <div class="card" style="max-width: 500px; margin: 3rem auto; padding: 2rem;">
      <h2 style="margin-bottom: 1.5rem;">Edit Kantin</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Nama Kantin:</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($canteen['name']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="photo">Foto:</label>
          <input type="file" id="photo" name="photo">
          <?php if (!empty($canteen['photo'])): ?>
            <small style="display: block; margin-top: 0.5rem;">Foto saat ini:</small>
            <img src="../images/<?php echo htmlspecialchars($canteen['photo']); ?>" width="120" class="img-thumbnail" style="margin-top: 0.5rem;"><br>
          <?php endif; ?>
        </div>
        
        <button type="submit" name="update" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">Batal</a>
      </form>
    </div>
  </div>
</body>
</html>