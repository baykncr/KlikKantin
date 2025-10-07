<?php
// Mulai output buffering untuk mencegah masalah "headers already sent"
ob_start();

include 'db.php';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));
    $role = 'mahasiswa'; // default role

    // Gunakan real_escape_string untuk keamanan dasar
    $escaped_username = $conn->real_escape_string($username);
    $escaped_password = $conn->real_escape_string($password);
    $escaped_role = $conn->real_escape_string($role);

    $check = $conn->query("SELECT * FROM users WHERE username='$escaped_username'");
    if ($check->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $conn->query("INSERT INTO users (username, password, role) VALUES ('$escaped_username', '$escaped_password', '$escaped_role')");
        
        // Bersihkan buffer sebelum redirect
        ob_end_clean();
        header("Location: login.php");
        exit();
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
  <title>Register - Klik Kantin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>🍱 Klik Kantin</h1>
        <p>Daftar akun mahasiswa baru</p>
      </div>
      
      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Buat username unik" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Buat password aman" required>
        </div>
        
        <button type="submit" name="register" class="btn-auth">Daftar</button>
      </form>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <div class="auth-footer">
        <p>Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
      </div>
    </div>
  </div>
</body>
</html>