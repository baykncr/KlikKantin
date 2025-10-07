<?php
include 'db.php';
$id = $_GET['id'];
$canteen = $conn->query("SELECT * FROM canteens WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Kantin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Edit Kantin</h2>
  <form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $canteen['id']; ?>">
    <label>Nama Kantin:</label>
    <input type="text" name="name" value="<?php echo $canteen['name']; ?>" required>

    <label>Foto Kantin:</label>
    <input type="file" name="photo">
    <img src="images/<?php echo $canteen['photo']; ?>" width="150"><br>

    <button type="submit" name="update">Simpan</button>
  </form>
</body>
</html>
