<?php
session_start();
include '../db.php';

// 🔒 Cek apakah login & role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$canteens = $conn->query("SELECT * FROM canteens ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Klik Kantin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <header class="navbar">
    <h1>Dashboard Admin</h1>
    <div class="nav-right">
      <span>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</span>
      <a href="../logout.php" class="btn-logout">Logout</a>
    </div>
  </header>

  <div class="container">
    <h1 style="margin-top: 2rem; margin-bottom: 1.5rem;">Daftar Kantin</h1>
    <a href="add_canteen.php" class="btn btn-primary" style="margin-bottom: 1.5rem;">+ Tambah Kantin</a>
    
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Kantin</th>
            <th>Foto</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($canteens->num_rows > 0): ?>
            <?php while ($c = $canteens->fetch_assoc()): ?>
            <tr>
              <td><?php echo $c['id']; ?></td>
              <td><?php echo htmlspecialchars($c['name']); ?></td>
              <td>
                <?php 
                $canteen_photo_path = !empty($c['photo']) ? '../images/' . htmlspecialchars($c['photo']) : 'https://via.placeholder.com/100x75?text=No+Image';
                ?>
                <img src="<?php echo $canteen_photo_path; ?>" width="100" class="img-thumbnail" alt="Foto Kantin" onerror="this.src='https://via.placeholder.com/100x75?text=No+Image'">
              </td>
              <td>
                <a href="edit_canteen.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_canteen.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kantin ini? Semua menu dan rating terkait juga akan terhapus!')">Hapus</a>
                <a href="edit_menu.php?canteen_id=<?php echo $c['id']; ?>" class="btn btn-sm btn-info">Edit Menu</a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center">Belum ada kantin yang terdaftar.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>