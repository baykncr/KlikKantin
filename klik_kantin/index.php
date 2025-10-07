<?php
session_start();

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Ambil data kantin
$result = $conn->query("SELECT * FROM canteens ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klik Kantin</title>
    <!-- No external CSS - gunakan inline untuk stabil -->
    <style>
        * {
            box-sizing: border-box; /* Pastikan padding tidak nambah width */
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0; /* Hapus padding body agar navbar full edge */
            color: #333;
        }
        .header {
            background-color: #ff8c00;
            color: white;
            padding: 15px 0; /* Padding vertikal saja, horizontal 0 untuk full width */
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 100%; /* Full width */
        }
        .nav-container {
            max-width: 1000px; /* Sama dengan container utama */
            margin: 0 auto; /* Center konten */
            display: flex;
            justify-content: space-between; /* Logo kiri, user kanan */
            align-items: center; /* Vertikal center */
            padding: 0 20px; /* Padding samping hanya di container, bukan header */
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-align: left; /* Logo di kiri */
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px; /* Jarak antara "Halo" dan logout */
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px; /* Padding samping untuk konten utama */
        }
        .canteens {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .canteen-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            margin: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            float: left; /* Fallback untuk browser lama */
        }
        .canteen-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .canteen-item h3 {
            color: #ff8c00;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background-color: #ff8c00;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #e67e22;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .no-canteens {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            margin: 20px 0;
        }
        .logout {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .logout:hover {
            background-color: #c82333;
        }
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column; /* Stack vertikal di mobile */
                gap: 10px;
                text-align: center;
                padding: 0 15px;
            }
            .user-info {
                justify-content: center;
                gap: 10px;
            }
            .canteen-item {
                width: 100%;
                margin: 10px 0;
                float: none;
            }
            .container {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav-container">
            <h1>🍱 Klik Kantin</h1>
            <div class="user-info">
                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 style="text-align: center; color: #ff8c00; margin-bottom: 30px;">Daftar Kantin</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="canteens">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="canteen-item">
                        <?php 
                        $image = !empty($row['photo']) ? 'images/' . $row['photo'] : 'https://via.placeholder.com/300x150/ff8c00/ffffff?text=No+Image';
                        ?>
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='https://via.placeholder.com/300x150/ff8c00/ffffff?text=No+Image';">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <a href="canteen.php?id=<?php echo $row['id']; ?>" class="btn">Lihat Menu</a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                            <a href="admin/edit_canteen.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Edit</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-canteens">
                <h3>Belum Ada Kantin</h3>
                <p>Belum ada kantin yang terdaftar. Silakan tambahkan kantin baru.</p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="admin/add_canteen.php" class="btn">Tambah Kantin</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <div style="text-align: center; margin-top: 30px;">
                <a href="admin/dashboard.php" class="btn btn-success">Dashboard Admin</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>