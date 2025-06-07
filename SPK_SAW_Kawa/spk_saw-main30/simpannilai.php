<?php
require 'config/koneksi.php';

$id_alternatif = $_POST['id_alternatif'];
$id_kriteria = $_POST['id_kriteria'];
$nilai = $_POST['nilai'];

$queryCek = "SELECT * FROM matriks WHERE id_alternatif = '$id_alternatif' AND id_kriteria = '$id_kriteria'";
$resultCek = mysqli_query($conn, $queryCek);

if(mysqli_num_rows($resultCek) > 0) {
    $queryUpdate = "UPDATE matriks SET nilai = '$nilai' WHERE id_alternatif = '$id_alternatif' AND id_kriteria = '$id_kriteria'";
    $exec = mysqli_query($conn, $queryUpdate);
    if($exec){
        header("Location: dashboard.php?url=mtrxkeputusan");
        exit;
    } else {
        echo "Gagal update data: ".mysqli_error($conn);
    }
} else {
    $queryInsert = "INSERT INTO matriks (id_alternatif, id_kriteria, nilai) VALUES ('$id_alternatif', '$id_kriteria', '$nilai')";
    $exec = mysqli_query($conn, $queryInsert);
    if($exec){
        header("Location: dashboard.php?url=mtrxkeputusan");
        exit;
    } else {
        echo "Gagal simpan data: ".mysqli_error($conn);
    }
}
?>
