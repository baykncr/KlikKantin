<?php
// Mulai output buffering untuk mencegah masalah "headers already sent"
ob_start();

session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    // Gunakan real_escape_string untuk keamanan dasar
    $escaped_username = $conn->real_escape_string($username);
    $escaped_password = $conn->real_escape_string($password);

    $query = $conn->query("SELECT * FROM users WHERE username='$escaped_username' AND password='$escaped_password'");

    if ($query->num_rows > 0) {
        $user = $query->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Bersihkan buffer sebelum redirect
        ob_end_clean();
        
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}

// Flush buffer di akhir jika tidak ada redirect
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Klik Kantin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>🍱 Klik Kantin</h1>
        <p>Masuk ke akun Anda</p>
      </div>
      
      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Masukkan username" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        </div>
        
        <button type="submit" name="login" class="btn-auth">Masuk</button>
      </form>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <div class="auth-footer">
        <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
      </div>
    </div>
  </div>
</body>
</html>