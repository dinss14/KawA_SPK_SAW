<?php
$conn = mysqli_connect("localhost", "root", "abc123", "spksaw", 3307);

// Check connection
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>
