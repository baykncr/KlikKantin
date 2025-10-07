<?php
session_start();

// 🔒 Cek login dulu sebelum akses halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// 🧩 Ambil data kantin, menu, dan rating
$id = intval($_GET['id']); // cegah SQL injection
$canteen = $conn->query("SELECT * FROM canteens WHERE id=$id")->fetch_assoc();
$menus = $conn->query("SELECT * FROM menus WHERE canteen_id=$id");
$ratings = $conn->query("SELECT * FROM ratings WHERE canteen_id=$id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($canteen['name'] ?? 'Kantin Tidak Ditemukan'); ?> - Klik Kantin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <h1>🍱 Klik Kantin</h1>
    <div class="nav-right">
      <span>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
      <a href="logout.php" class="btn-logout">Logout</a>
    </div>
  </header>

  <div class="container">
    <!-- Header Kantin -->
    <div class="text-center" style="margin-top: 2rem; margin-bottom: 2rem;">
      <h1 style="margin-bottom: 1rem;"><?php echo htmlspecialchars($canteen['name'] ?? 'Kantin Tidak Ditemukan'); ?></h1>
      <a href="index.php" class="btn btn-secondary">← Kembali ke Daftar Kantin</a>
    </div>

    <!-- Daftar Menu -->
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem;">Daftar Menu</h2>
    <div class="menu-grid">
      <?php if ($menus->num_rows > 0): ?>
        <?php while ($m = $menus->fetch_assoc()): ?>
          <div class="card">
            <?php 
            $menu_photo_path = !empty($m['photo']) ? 'images/' . htmlspecialchars($m['photo']) : 'https://via.placeholder.com/280x180?text=No+Image';
            ?>
            <img src="<?php echo $menu_photo_path; ?>" alt="Foto <?php echo htmlspecialchars($m['name']); ?>" onerror="this.src='https://via.placeholder.com/280x180?text=No+Image'">
            <h5><?php echo htmlspecialchars($m['name']); ?></h5>
            <p style="font-weight: bold; color: #28a745;">Rp<?php echo number_format($m['price'], 0, ',', '.'); ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-info text-center" style="grid-column: 1 / -1;">Belum ada menu untuk kantin ini.</div>
      <?php endif; ?>
    </div>

    <!-- Rating & Komentar -->
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem;">Rating & Komentar</h2>
    <div class="card rating-form" style="margin-bottom: 3rem;">
      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="canteen_id" value="<?php echo $id; ?>">
        <div class="form-group">
          <label for="user_name">Nama Anda</label>
          <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
        <div class="form-group">
          <label for="comment">Komentar</label>
          <textarea id="comment" name="comment" rows="3" placeholder="Tulis komentar Anda di sini..." required></textarea>
        </div>
        <div class="form-group">
          <label for="rating">Rating (1-5)</label>
          <input type="number" id="rating" name="rating" min="1" max="5" required>
        </div>
        <div class="form-group">
          <label for="photo">Upload Foto (opsional)</label>
          <input type="file" id="photo" name="photo">
        </div>
        <button type="submit" name="submit_rating" class="btn btn-primary">Kirim Rating</button>
      </form>
    </div>

    <h3 style="margin-bottom: 1.5rem;">Ulasan Pengguna</h3>
    <div class="rating-grid rating-section">
      <?php if ($ratings->num_rows > 0): ?>
        <?php while ($r = $ratings->fetch_assoc()): ?>
          <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
              <strong><?php echo htmlspecialchars($r['user_name']); ?></strong>
              <span class="rating-badge"><?php echo $r['rating']; ?> ⭐</span>
            </div>
            <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($r['comment']); ?></p>
            <?php if ($r['photo']): ?>
              <img src="images/<?php echo htmlspecialchars($r['photo']); ?>" alt="Foto ulasan" onerror="this.src='https://via.placeholder.com/150x100?text=No+Image'">
            <?php endif; ?>
            <small style="margin-top: 0.5rem;"><?php echo date('d M Y H:i', strtotime($r['created_at'])); ?></small>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-info text-center" style="grid-column: 1 / -1;">Belum ada ulasan untuk kantin ini. Jadilah yang pertama!</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>