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

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
<div class="card-header m-0 font-weight-bold" style="text-align: center; background-color: #987661; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">Edit Data Kriteria</div>
  <?php
      require 'config/koneksi.php';
      $sql=mysqli_query($conn, "select * from kriteria where id_kriteria='$_GET[id]' ");
      if ($data=mysqli_fetch_array($sql)) {
        
        // Initialize error message
        $bobotErr = "";
        
        // Check if form was submitted with invalid bobot
        if(isset($_GET['error']) && $_GET['error'] == 'bobot') {
          $bobotErr = "*Bobot harus bilangan bulat antara 1-10";
        }
   ?>
   <div class="card-body">
    <form action="simpaneditkrit.php" method="post" class="form-horizontal" enctype="multipart/form-data">
      <div class="form-group cols-sm-6">
        <label style="color: black">ID Kriteria</label>
        <input type="text" name="id_kriteria" style="color: black" value="<?php echo $data['id_kriteria']; ?>" class="form-control" readonly>
      </div>

      <div class="form-group cols-sm-6">
        <label style="color: black">Kriteria</label>
        <input type="text" name="kriteria" style="color: black" value="<?php echo $data['kriteria']; ?>" class="form-control" required>
      </div>
    
      <div class="form-group cols-sm-6">
        <label style="color: black">Bobot (1-10)</label>
        <input type="number" step="1" min="1" max="10" name="bobot" style="color: black" 
               value="<?php echo (int)$data['bobot']; ?>" class="form-control" required>
        <span class="error"><?php echo $bobotErr; ?></span>
      </div>

      <div class="form-group cols-sm-6">
        <label style="color: black">Atribut</label>
        <select class="form-control" name="atribut" style="color: black" required>
          <option value="keuntungan" <?php if($data['atribut'] == 'keuntungan'){echo 'selected';}?>>Keuntungan</option>
          <option value="biaya" <?php if($data['atribut'] == 'biaya'){echo 'selected';}?>>Biaya</option> 
        </select>
      </div>

      <div class="form-group cols-sm-6">
        <button type="submit" name="edit" class="btn btn-secondary btn-icon-split" style="background-color: #88603C;">
          <span class="icon text-white-50">
              <i class="fas fa-user-edit"></i>
          </span>
          <span class="text">Simpan Perubahan</span>
        </button>
      </div>
    </form>
    <?php } ?>
  </div>
</div>

</body>
</html>