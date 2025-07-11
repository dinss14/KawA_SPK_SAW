<?php
if(!isset($_SESSION['username']))
{
?>
<script type="text/javascript">
    alert ('Anda Belum Login');
    window.location='index.php';
</script>
<?php
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>EDIT DATA</title>

  <style type="text/css">
    form {
      width: 100%;
    }
    body {
      font-family: "Verdana";
    }
    .error {
      font-size: 11px;
      font-weight: bold;
      color: firebrick;
      margin-bottom: 6px;
    }
  </style>
</head>

<body id="page-top">
<br>
<div class="card shadow" style="width: 50%;">
<div class="card-header m-0 font-weight-bold" style="text-align: center; background-color: #987661; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">Isi Nilai Matriks</div>
   <div class="card-body">
    <form action="simpannilai.php" method="post" class="form-horizontal" enctype="multipart/form-data">
    
      <div class="form-group cols-sm-6">
        <label style="color: black">Nama Alternatif</label>
            <select class="form-control" name="id_alternatif" style="color: black" required>
                <option value="" hidden></option>
                <?php
                    require 'config/koneksi.php';
                    $sql=mysqli_query($conn, "select * from alternatif");
                    while ($data=mysqli_fetch_array($sql)) {
                        $id_alternatif = $data['id_alternatif'];
                        $nama_alternatif = $data['nama_alternatif'];
                        echo "<option value = '$id_alternatif'>$nama_alternatif</option>";
                    }
                ?>
            </select>
      </div>

      <div class="form-group cols-sm-6">
        <label style="color: black">Kriteria</label>
            <select class="form-control" name="id_kriteria" style="color: black" required>
                <option value="" hidden></option>
                <?php
                    $sql=mysqli_query($conn, "select * from kriteria");
                    while ($data=mysqli_fetch_array($sql)) {
                        $id_kriteria = $data['id_kriteria'];
                        $kriteria = $data['kriteria'];
                        echo "<option value = '$id_kriteria'>$kriteria</option>";
                    }
                ?>
            </select>
      </div>

      <div class="form-group cols-sm-6">
        <label style="color: black">Nilai (1-5)</label>
        <input type="number" name="nilai" min="1" max="5" step="1" class="form-control" style="color: black" required>
        <span class="error">
          <?php 
          if(isset($_GET['error']) && $_GET['error'] == 'nilai') {
            echo "*Nilai harus bilangan bulat antara 1-5";
          }
          ?>
        </span>
      </div>
        
      <div class="form-group cols-sm-6">
        <button type="submit" name="simpan" class="btn btn-secondary btn-icon-split" style="background-color: #88603C;">
          <span class="icon text-white-50">
              <i class="fas fa-plus"></i>
          </span>
          <span class="text">Simpan</span>
        </button>
      </div>

    </form>
    
  </div>
</div>

</body>
</html>