<?php
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda Belum Login'); window.location='index.php';</script>";
    exit;
}

require 'config/koneksi.php';

// Get all alternatives
$alternatives = [];
$altQuery = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif");
while ($alt = mysqli_fetch_assoc($altQuery)) {
    $alternatives[$alt['id_alternatif']] = $alt['nama_alternatif'];
}

// Get all criteria
$criteria = [];
$critQuery = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");
while ($crit = mysqli_fetch_assoc($critQuery)) {
    $criteria[$crit['id_kriteria']] = [
        'name' => $crit['kriteria'],
        'attribute' => strtolower($crit['atribut']) // buat lowercase untuk konsistensi
    ];
}

// Get raw matrix values
$rawMatrix = [];
$matrixQuery = mysqli_query($conn, "SELECT * FROM matriks ORDER BY id_kriteria, id_alternatif");
while ($m = mysqli_fetch_assoc($matrixQuery)) {
    $rawMatrix[$m['id_kriteria']][$m['id_alternatif']] = $m['nilai'];
}

// Calculate min and max for each criterion
$minMaxValues = [];
foreach ($criteria as $critId => $critData) {
    if (isset($rawMatrix[$critId])) {
        $values = array_values($rawMatrix[$critId]);
        $minMaxValues[$critId] = [
            'min' => min($values),
            'max' => max($values)
        ];
    }
}

$R = []; // deklarasi array normalisasi
foreach ($alternatives as $altId => $altName) {
    foreach ($criteria as $critId => $critData) {
        $value = 0;
        if (isset($rawMatrix[$critId][$altId]) && $rawMatrix[$critId][$altId] != 0) {
            $rawValue = $rawMatrix[$critId][$altId];

            if ($critData['attribute'] == 'biaya' || $critData['attribute'] == 'cost') {
                // Normalisasi atribut biaya (cost): nilai min / nilai aktual
                $value = $minMaxValues[$critId]['min'] / $rawValue;
            } else {
                // Normalisasi atribut keuntungan (benefit): nilai aktual / nilai max
                $value = $rawValue / $minMaxValues[$critId]['max'];
            }
        }
        $R[$altId][$critId] = round($value, 3); // Simpan hasil normalisasi
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Matriks Ternormalisasi</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      border: 1px solid #ddd;
    }
    th, td {
      text-align: center;
      padding: 10px;
      border: 1px solid #ddd;
      color: black;
    }
    body {
      font-family: Verdana, sans-serif;
    }
    .card-header {
      text-align: center;
      background-color: #987661;
      color: white;
      font-weight: bold;
      font-size: larger;
      border-top-left-radius: 1rem; 
      border-top-right-radius: 1rem;
      padding: 10px;
    }
  </style>
</head>
<body>

<div class="card">
  <div class="card-header">Matriks Ternormalisasi (R)</div>
  <div class="card-body">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Alternatif</th>
          <?php foreach ($criteria as $critId => $critData): ?>
            <th>C<sub><?= $critId ?></sub></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($alternatives)): ?>
          <tr>
            <td colspan="<?= count($criteria) + 2 ?>">Tidak ada data alternatif</td>
          </tr>
        <?php else: ?>
          <?php $no = 1; ?>
          <?php foreach ($alternatives as $altId => $altName): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($altName) ?></td>
              <?php foreach ($criteria as $critId => $critData): ?>
                <td><?= isset($R[$altId][$critId]) ? $R[$altId][$critId] : 0 ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
