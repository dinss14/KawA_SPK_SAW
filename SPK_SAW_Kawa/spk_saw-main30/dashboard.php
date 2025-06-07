<?php
session_start();
if(!isset($_SESSION['username'])) {
  echo "<script>alert('Anda Belum Login, Silahkan Login'); window.location = 'index.php';</script>";
} 
session_write_close();

require 'config/koneksi.php';

ob_start();
require 'mtrx_ternormalisasi.php'; // supaya $R tersedia
ob_end_clean();

// Inisialisasi $R jika belum terdefinisi
if (!isset($R)) {
    $R = array();
}

$sql = mysqli_query($conn, "SELECT bobot FROM kriteria ORDER BY id_kriteria");
$W = array();
while ($data = mysqli_fetch_object($sql)) {
    $W[] = $data->bobot;
}

$P = array();
$m = count($W);
foreach ($R as $i => $r) {
    for ($j = 0; $j < $m; $j++) {
      if (isset($r[$j]) && isset($W[$j])) {
        $P[$i] = (isset($P[$i]) ? $P[$i] : 0) + $r[$j] * $W[$j];
      }
    }
}

// Cek apakah $P ada isinya sebelum cari max
if (!empty($P)) {
    $maxValue = max($P);
    $maxKeys = array_keys($P, $maxValue);
    $maxIndex = $maxKeys[0];
} else {
    $maxIndex = null;
}

$rekomendasi_terbaik = 'Belum tersedia';
if ($maxIndex !== null) {
    // Pastikan $maxIndex aman dari SQL Injection
    $maxIndexInt = (int)$maxIndex;
    $altQuery = mysqli_query($conn, "SELECT nama_alternatif FROM alternatif WHERE id_alternatif = $maxIndexInt LIMIT 1");
    if ($altRow = mysqli_fetch_assoc($altQuery)) {
        $rekomendasi_terbaik = $altRow['nama_alternatif'];
    }
}

$sql_jumlah_kriteria = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kriteria");
$data_jumlah_kriteria = mysqli_fetch_assoc($sql_jumlah_kriteria);
$total_kriteria = $data_jumlah_kriteria['total'];

$sql_jumlah_alt = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alternatif");
$data_jumlah_alt = mysqli_fetch_assoc($sql_jumlah_alt);
$total_alternatif = $data_jumlah_alt['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>User</title>

  <link rel="icon" type="image/png" href="assets/img/logo-jawaa2.png" sizes="300x300">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
      background-color: #F4E9DD;  
      font-family: "Verdana";
    }
    .card {
      border-radius: 15px;
      transition: all 0.3s ease-in-out;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #987661">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" style="color: white; padding-top: 50px;">
        <div class="sidebar-brand-icon">
          <img src="assets/img/logo-jawaa.png" alt="Logo Jawa" style="width: 70px; height: auto;">
        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 20px; font-weight: bold;">KawA</div>
      </a>
      <hr class="sidebar-divider" style="margin-top: 30px;">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-home"></i><span>Home</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-file-alt"></i><span>Data</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?url=alternatif">Alternatif</a>
            <a class="collapse-item" href="?url=kriteria">Kriteria</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
          <i class="fas fa-fw fa-dice-d20"></i><span>Matriks</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="?url=mtrxkeputusan">Keputusan</a>
            <a class="collapse-item" href="?url=mtrxternormalisasi">Ternormalisasi</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?url=nilaipreferensi">
          <i class="fas fa-prescription"></i><span>Nilai Preferensi</span></a>
      </li>
      <hr class="sidebar-divider logout-divider d-none d-md-block">
      <li class="nav-item nav-logout">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-door-open"></i><span>Keluar</span></a>
      </li>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" style="background-color: #F4E9DD;">
        <div class="container-fluid mt-4">
          <?php
          if (!isset($_GET['url'])) {
          ?>
            <h2 class="mb-4 text-dark font-weight-bold">Selamat Datang <?php echo $_SESSION['username']; ?> di Sistem Rekomendasi Kopi KawA!</h2>
            <div class="mb-4" style="font-size: 18px; font-weight: 500;">
              Sistem ini membantu Anda memilih kafe kopi terbaik berdasarkan beberapa kriteria<br>
              menggunakan metode Sistem Pendukung Keputusan (SPK) berbasis SAW.
            </div>
            <div class="row">
              <div class="col-md-4 mb-4">
                <div class="card shadow border-left-success py-2">
                  <div class="card-body">
                    <h5 class="card-title font-weight-bold text-success">Total Alternatif</h5>
                    <p class="card-text"><?php echo $total_alternatif; ?> jenis kopi</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="card shadow border-left-info py-2">
                  <div class="card-body">
                    <h5 class="card-title font-weight-bold text-info">Total Kriteria</h5>
                    <p class="card-text"><?php echo $total_kriteria; ?> kriteria</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="card shadow border-left-warning py-2">
                  <div class="card-body">
                    <h5 class="card-title font-weight-bold text-warning">Rekomendasi Terbaik</h5>
                    <p class="card-text"><?php echo $rekomendasi_terbaik; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="container mt-5 mb-5">
              <h4 class="font-weight-bold text-dark mb-3">Tahapan Sistem Pendukung Keputusan (SAW)</h4>
              <p class="text-muted">Berikut langkah-langkah yang dilakukan sistem untuk menghasilkan rekomendasi terbaik:</p>

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="card p-3 shadow">
                    <h5 class="text-success">1. Kriteria</h5>
                    <p>Menentukan faktor penilaian seperti harga, rating, lokasi, dsb.</p>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="card p-3 shadow">
                    <h5 class="text-info">2. Bobot</h5>
                    <p>Memberi nilai kepentingan pada tiap kriteria.</p>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="card p-3 shadow">
                    <h5 class="text-primary">3. Matriks Keputusan</h5>
                    <p>Menyusun data alternatif berdasarkan kriteria.</p>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="card p-3 shadow">
                    <h5 class="text-warning">4. Normalisasi</h5>
                    <p>Menstandarkan nilai untuk membuatnya bisa dibandingkan.</p>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="card p-3 shadow">
                    <h5 class="text-danger">5. Preferensi</h5>
                    <p>Mengalikan nilai dengan bobot dan menjumlahkan untuk hasil akhir.</p>
                  </div>
                </div>
              </div>
            </div>

          <?php
          } else {
            require 'routes/router.php';
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background: #167395">
          <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold; color: white;">Ingin Keluar dari Halaman Ini?</h5>
          <button class="close" style="color: white;" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Tekan "Keluar" jika ingin mengakhiri sesi.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-danger" href="logout.php">Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>