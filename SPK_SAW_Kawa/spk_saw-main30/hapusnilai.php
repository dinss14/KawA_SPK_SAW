<?php 
require 'config/koneksi.php';
$id = $_GET['id'];

$sql = mysqli_query($conn, "DELETE FROM matriks WHERE id_alternatif='$id'");

if ($sql) {
    header("Location: dashboard.php?url=mtrxkeputusan");
    exit;
}
?>
