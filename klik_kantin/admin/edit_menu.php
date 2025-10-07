<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$canteen_id = intval($_GET['canteen_id']);
$canteen = $conn->query("SELECT * FROM canteens WHERE id=$canteen_id")->fetch_assoc();

// Ambil pesan error/success dari URL
$error = isset($_GET['error']) ? $_GET['error'] : null;
$success = isset($_GET['success']) ? $_GET['success'] : null;

$menus = $conn->query("SELECT * FROM menus WHERE canteen_id=$canteen_id ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Menu - <?php echo htmlspecialchars($canteen['name'] ?? 'Unknown'); ?></title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <h2 style="margin-top: 2rem; margin-bottom: 1.5rem;">Edit Menu <?php echo htmlspecialchars($canteen['name'] ?? 'Unknown'); ?></h2>
    
    <?php if ($success == 'menu_deleted'): ?>
      <div class="alert alert-success">Menu berhasil dihapus!</div>
    <?php elseif ($success == 'menu_saved'): ?>
      <div class="alert alert-success">Menu berhasil disimpan!</div>
    <?php endif; ?>
    
    <?php if ($error): ?>
      <div class="alert alert-danger">
        <?php 
        if ($error == 'menu_not_found') {
            echo "Menu tidak ditemukan atau sudah dihapus!";
        } elseif ($error == 'delete_failed') {
            echo "Gagal menghapus menu. Silakan coba lagi.";
        } elseif ($error == 'param_missing') {
            echo "Parameter yang diperlukan untuk menghapus menu tidak lengkap.";
        } else {
            echo "Terjadi kesalahan yang tidak diketahui: " . htmlspecialchars($error);
        }
        ?>
      </div>
    <?php endif; ?>

    <a href="add_menu.php?canteen_id=<?php echo $canteen_id; ?>" class="btn btn-primary" style="margin-bottom: 1.5rem;">+ Tambah Menu</a>
    
    <?php if ($canteen): ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Nama Menu</th>
              <th>Harga</th>
              <th>Foto</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($menus->num_rows > 0): ?>
              <?php while ($m = $menus->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td>Rp<?php echo number_format($m['price'], 0, ',', '.'); ?></td>
                <td>
                  <?php 
                  $menu_photo_path = !empty($m['photo']) ? '../images/' . htmlspecialchars($m['photo']) : 'https://via.placeholder.com/100x75?text=No+Image';
                  ?>
                  <img src="<?php echo $menu_photo_path; ?>" width="100" class="img-thumbnail" alt="Foto menu" onerror="this.src='https://via.placeholder.com/100x75?text=No+Image'">
                </td>
                <td>
                  <a href="add_menu.php?edit_id=<?php echo $m['id']; ?>&canteen_id=<?php echo $canteen_id; ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete_menu.php?id=<?php echo $m['id']; ?>&canteen_id=<?php echo $canteen_id; ?>" 
                     class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini? Data tidak bisa dikembalikan!')">Hapus</a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">Belum ada menu untuk kantin ini.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-danger text-center">Kantin tidak ditemukan!</div>
    <?php endif; ?>
    
    <br><a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
  </div>
</body>
</html>