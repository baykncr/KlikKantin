<?php
ob_start(); // Mulai output buffering

session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    ob_end_clean();
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $photo = null;

    // Gunakan real_escape_string
    $escaped_name = $conn->real_escape_string($name);

    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $target_file = "../images/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("INSERT INTO canteens (name, photo) VALUES (?, ?)");
    $stmt->bind_param("ss", $escaped_name, $photo);
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
  <title>Tambah Kantin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <div class="card" style="max-width: 500px; margin: 3rem auto; padding: 2rem;">
      <h2 style="margin-bottom: 1.5rem;">Tambah Kantin Baru</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Nama Kantin</label>
          <input type="text" id="name" name="name" placeholder="Masukkan nama kantin" required>
        </div>
        <div class="form-group">
          <label for="photo">Foto Kantin</label>
          <input type="file" id="photo" name="photo">
        </div>
        <button type="submit" name="add" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">Tambah Kantin</button>
        <a href="dashboard.php" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">Batal</a>
      </form>
    </div>
  </div>
</body>
</html>