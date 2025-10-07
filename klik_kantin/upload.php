<?php
ob_start(); // Mulai output buffering

include 'db.php';

// ========== SIMPAN RATING BARU ==========
if (isset($_POST['submit_rating'])) {
    $canteen_id = intval($_POST['canteen_id']);
    $user = $conn->real_escape_string($_POST['user_name']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $rating = intval($_POST['rating']);
    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $target_file = "images/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("INSERT INTO ratings (canteen_id, user_name, comment, rating, photo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $canteen_id, $user, $comment, $rating, $photo);
    $stmt->execute();
    $stmt->close();

    ob_end_clean(); // Bersihkan buffer sebelum redirect
    header("Location: canteen.php?id=" . $canteen_id);
    exit;
}

// ========== UPDATE KANTIN (ADMIN) ==========
// Catatan: admin_edit.php di root folder memanggil ini.
// Sebaiknya fungsi ini dipindahkan ke admin/edit_canteen.php jika admin_edit.php dihapus.
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $current_photo = ''; // Ambil foto lama dari DB jika tidak ada upload baru

    // Ambil foto lama
    $res = $conn->query("SELECT photo FROM canteens WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $current_photo = $row['photo'];
    }

    $photo = $current_photo; // Default ke foto lama

    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $target_file = "images/" . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE canteens SET name=?, photo=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $photo, $id);
    $stmt->execute();
    $stmt->close();

    ob_end_clean(); // Bersihkan buffer sebelum redirect
    header("Location: index.php"); // Redirect ke halaman utama setelah update
    exit;
}

ob_end_flush(); // Flush buffer di akhir jika tidak ada redirect
?>