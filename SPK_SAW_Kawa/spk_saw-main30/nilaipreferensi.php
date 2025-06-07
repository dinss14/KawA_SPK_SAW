<?php
if(!isset($_SESSION['username'])) {
    echo "<script>alert('Anda Belum Login'); window.location='index.php';</script>";
    exit;
}

require 'config/koneksi.php';

// Ambil data matriks ternormalisasi (R) dan bobot kriteria (W)
ob_start();
require 'mtrx_ternormalisasi.php'; // File ini harus menginisialisasi $R
ob_end_clean();

if (empty($R)) {
    echo "<p style='color:red;'>Data normalisasi kosong. Pastikan Anda sudah mengisi nilai dan normalisasi berhasil.</p>";
}

// Ambil bobot kriteria dari database
$sql = mysqli_query($conn, "SELECT bobot FROM kriteria ORDER BY id_kriteria");
$W = array();
while ($data = mysqli_fetch_assoc($sql)) {
    $W[] = $data['bobot'];
}

// Hitung nilai preferensi (P) dengan rumus SAW: P = Σ(Rij * Wj)
$P = array();
$alternatifNames = array();

// Ambil nama alternatif untuk ditampilkan
$altQuery = mysqli_query($conn, "SELECT id_alternatif, nama_alternatif FROM alternatif ORDER BY id_alternatif");
while ($alt = mysqli_fetch_assoc($altQuery)) {
    $alternatifNames[$alt['id_alternatif']] = $alt['nama_alternatif'];
}

// Pastikan $R terdefinisi dan berbentuk array
if (!isset($R) || !is_array($R)) {
    $R = array();
}

// Hitung nilai preferensi untuk setiap alternatif
foreach ($R as $id_alternatif => $nilai_kriteria) {
    $total = 0;
    foreach ($nilai_kriteria as $j => $nilai) {
        $bobot = isset($W[$j]) ? $W[$j] : 0;
        $total += $nilai * $bobot;
    }
    $P[$id_alternatif] = $total;
}

// Urutkan dari nilai tertinggi ke terendah
arsort($P);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nilai Preferensi SAW</title>
  
  <style>
    body {
      font-family: "Verdana";
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .card-header {
      background-color: #987661;
      color: white;
      font-weight: bold;
      border-top-left-radius: 15px !important;
      border-top-right-radius: 15px !important;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .highlight {
      background-color: #fff3cd;
      font-weight: bold;
    }
    .btn-back {
      background-color: #88603C;
      color: white;
      font-weight: bold;
    }
    .btn-back:hover {
      background-color: #76532f;
      color: white;
    }
  </style>
  
  <!-- Tambahkan link Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <div class="container-fluid mt-4">
    <div class="card shadow mb-4">
      <div class="card-header py-3 text-center">
        <h4 class="m-0 font-weight-bold">Nilai Preferensi (Metode SAW)</h4>
      </div>
      
      <div class="card-body">
        <a href="dashboard.php" class="btn btn-back mb-4">
          <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <div class="alert alert-info">
          <strong>Keterangan:</strong> Nilai preferensi dihitung dengan rumus SAW: 
          <code>P = Σ(Rij × Wj)</code> dimana Rij adalah matriks ternormalisasi dan Wj adalah bobot kriteria.
          Nilai tertinggi merupakan rekomendasi terbaik.
        </div>
        
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Ranking</th>
                <th>ID Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Nilai Preferensi (P)</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $ranking = 1;
              foreach ($P as $id_alternatif => $nilai) {
                  $nama_alternatif = isset($alternatifNames[$id_alternatif]) ? $alternatifNames[$id_alternatif] : 'Tidak Diketahui';
                  $isBest = ($ranking == 1) ? 'highlight' : '';
                  $keterangan = ($ranking == 1) ? '<span class="badge badge-success">REKOMENDASI TERBAIK</span>' : '';
                  
                  echo "<tr class='{$isBest}'>";
                  echo "<td>{$ranking}</td>";
                  echo "<td>A<sub>{$id_alternatif}</sub></td>";
                  echo "<td>{$nama_alternatif}</td>";
                  echo "<td>" . number_format($nilai, 4) . "</td>";
                  echo "<td>{$keterangan}</td>";
                  echo "</tr>";
                  
                  $ranking++;
              }
              
              // Jika tidak ada data
              if (empty($P)) {
                  echo "<tr><td colspan='5' class='text-center'>Tidak ada data alternatif atau nilai preferensi</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        
        <div class="mt-4">
          <h5>Keterangan Proses:</h5>
          <ol>
            <li>Normalisasi matriks keputusan (X) menjadi matriks ternormalisasi (R)</li>
            <li>Setiap nilai R dikalikan dengan bobot kriteria (W) yang sesuai</li>
            <li>Jumlahkan hasil perkalian untuk mendapatkan nilai preferensi (P)</li>
            <li>Alternatif dengan nilai P tertinggi adalah rekomendasi terbaik</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>