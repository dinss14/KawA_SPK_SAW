<?php
if(!isset($_SESSION['username']))
{
    echo "<script>
            alert('Anda Belum Login');
            window.location='index.php';
          </script>";
    exit;
}
require 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Matriks Keputusan (X)</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      border: 1px solid #ddd;
    }
    th, td {
      text-align: left;
      padding: 16px;
    }
    body {
      font-family: "Verdana";
    }
    .navbar {
      width: 100%;
      background-color: royalblue;
      overflow: auto;
      color: white;
    }
  </style>
</head>

<body>
<br>
<div class="card shadow mb-5">
  <div class="card-header py-3" style="text-align: center; background-color: #987661; color: white; font-weight:bold; font-size: larger; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">Matriks Keputusan (X)</div>

  <div class="card-body">
    <a href="dashboard.php?url=tambahnilai" class="btn btn-success btn-icon-split" style="background: #88603C">
      <span class="icon text-white-50"><i class="fas fa-user-plus"></i></span>
      <span class="text" style="font-weight: bold;">Isi Nilai</span>
    </a>

    <br><br>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th style="color: black">No</th>
            <th style="color: black">Alternatif</th>
            <th style="color: black; text-align: center;" colspan="<?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kriteria")) ?>">Kriteria</th>
            <th style="color: black">Action</th>
          </tr>
          <tr>
            <?php
              echo "<th></th><th></th>";
              $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
              while ($k = mysqli_fetch_assoc($kriteria)) {
                  echo "<th style='color: black'>C<sub>{$k['id_kriteria']}</sub></th>";
              }
              echo "<th></th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $alt = mysqli_query($conn, "SELECT DISTINCT a.id_alternatif FROM matriks m JOIN alternatif a ON m.id_alternatif = a.id_alternatif ORDER BY a.id_alternatif ASC");
          while ($row = mysqli_fetch_assoc($alt)) {
              $no++;
              echo "<tr>";
              echo "<td style='color:black'>{$no}</td>";
              echo "<td style='color:black'>A<sub>{$row['id_alternatif']}</sub></td>";

              // Ambil semua kriteria dan tampilkan nilai untuk alternatif ini
              $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
              while ($k = mysqli_fetch_assoc($kriteria)) {
                  $id_k = $k['id_kriteria'];
                  $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM matriks WHERE id_alternatif='{$row['id_alternatif']}' AND id_kriteria='$id_k'"));
                  $nilai_disp = isset($nilai['nilai']) ? round($nilai['nilai'], 2) : '-';
                  echo "<td style='color:black'>{$nilai_disp}</td>";
              }

              echo "<td>
                      <a href='#modalDelete' data-toggle='modal' onclick=\"$('#modalDelete #formDelete').attr('action', 'hapusnilai.php?id={$row['id_alternatif']}')\" class='btn btn-danger btn-circle' style='background: #c43939'>
                        <i class='fa fa-trash-alt'></i>
                      </a>
                    </td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background: #c43939">
          <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold; color: white;">Ingin Hapus Data Ini?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formDelete" action="" method="POST">
            <button class="btn btn-danger" style="background: #c43939" type="submit">Hapus</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
