<?php 
require 'config/koneksi.php';

$id = $_POST['id_alternatif'];
$nama = $_POST['nama'];

// Update data
$sql = mysqli_query($conn, "UPDATE alternatif SET nama_alternatif='$nama' WHERE id_alternatif='$id'");

// Jika berhasil, lakukan resequencing ID
if ($sql) {
    // Resequencing ID agar tetap urut
    mysqli_query($conn, "SET @no = 0");
    mysqli_query($conn, "UPDATE alternatif SET id_alternatif = @no := @no + 1");
    mysqli_query($conn, "ALTER TABLE alternatif AUTO_INCREMENT = 1");

    // Redirect tanpa alert
    header("Location: dashboard.php?url=alternatif");
    exit;
} else {
    // Handle jika gagal (opsional)
    echo "Terjadi kesalahan saat mengubah data.";
}
?>