<?php 
require 'config/koneksi.php';
$id = $_GET['id'];

// Hapus data berdasarkan ID
$sql = mysqli_query($conn, "DELETE FROM alternatif WHERE id_alternatif='$id'");

if ($sql) {
    // Mengurutkan ulang ID
    mysqli_query($conn, "SET @num := 0");
    mysqli_query($conn, "UPDATE alternatif SET id_alternatif = @num := @num + 1");

    // Reset AUTO_INCREMENT
    $result = mysqli_query($conn, "SELECT MAX(id_alternatif) AS max_id FROM alternatif");
    $row = mysqli_fetch_assoc($result);
    $next_id = $row['max_id'] + 1;
    mysqli_query($conn, "ALTER TABLE alternatif AUTO_INCREMENT = $next_id");

    // Redirect tanpa alert
    header("Location: dashboard.php?url=alternatif");
    exit;
} else {
    // Jika gagal, redirect juga bisa dengan pesan error (opsional)
    header("Location: dashboard.php?url=alternatif&error=hapus_gagal");
    exit;
}
?>
