<?php 
require 'config/koneksi.php';

$id       = $_POST['id_kriteria'];
$kriteria = $_POST['kriteria'];
$bobot    = $_POST['bobot'];
$atribut  = $_POST['atribut'];

// Jalankan query update
$sql = mysqli_query($conn, "UPDATE kriteria SET kriteria='$kriteria', bobot='$bobot', atribut='$atribut' WHERE id_kriteria='$id'");

// Jika berhasil, langsung redirect tanpa alert
if ($sql) {
    header("Location: dashboard.php?url=kriteria");
    exit;
} else {
    // Jika gagal, redirect ke halaman yang sama dengan pesan error
    header("Location: dashboard.php?url=kriteria&error=update_failed");
    exit;
}
?>
