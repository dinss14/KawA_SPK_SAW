<?php
if(!isset($_SESSION['username'])) {
    echo "<script>alert('Anda Belum Login'); window.location='index.php';</script>";
    exit;
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

  <title>HOME PAGE</title>
  
  <!-- Tambahkan CSS dan library yang diperlukan -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    table {
      border-collapse: collapse;
      border-spacing: 0;
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
    <div class="card-header py-3" style="text-align: center; background-color: #987661; color: white; font-weight:bold; font-size: larger; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
        Data Kriteria
    </div>

    <div class="card-body">
        <a href="dashboard.php?url=tambahkriteria" class="btn btn-success btn-icon-split" style="background: #88603C">
          <span class="icon text-white-50">
            <i class="fas fa-user-plus"></i>
          </span>
          <span class="text" style="font-weight: bold;">Tambah Data</span>
        </a>

        <br> 
        <br>

        <p style="color: black; font-weight: bold">Tabel berikut ini menampilkan bobot preferensi dari setiap kriteria.</p>
        
        <div class="table-responsive">
          <table class="table table-bordered">
              <thead>
                 <tr>
                    <th scope="col" style="color: black">No</th>
                    <th scope="col" style="color: black">Simbol</th>
                    <th scope="col" style="color: black">Kriteria</th>
                    <th scope="col" style="color: black">Bobot</th>
                    <th scope="col" style="color: black">Atribut</th>
                    <th scope="col" style="color: black">Action</th>
                  </tr>
                </thead>

              <tbody>
                <?php
                require 'config/koneksi.php';
                $no = 0;
                $sql = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                while ($data = mysqli_fetch_assoc($sql)) {
                    $no++;  // Nomor urut sesuai baris tampil
                ?>
                <tr>
                  <th scope="row" style="color: black"><?= $no ?></th> 
                  <th scope="row" style="color: black">C<sub><?= $no ?></sub></th> 
                  <td style="color: black"><?= htmlspecialchars($data['kriteria']) ?></td>
                  <td style="color: black"><?= htmlspecialchars($data['bobot']) ?></td>
                  <td style="color: black"><?= htmlspecialchars($data['atribut']) ?></td>
                  <td>
                    <a href="dashboard.php?url=editkriteria&id=<?= $data['id_kriteria'] ?>" class="btn btn-secondary btn-circle" style="background: #2b4280" title="Edit">
                      <i class="fa fa-edit"></i>
                    </a>

                    <a href="#modalDelete" data-toggle="modal" onclick="setDeleteId('<?= $data['id_kriteria'] ?>')" class="btn btn-danger btn-circle" style="background: #c43939" title="Hapus">
                      <i class="fa fa-trash-alt"></i>
                    </a>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
          </table>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formDelete" method="GET" action="hapuskriteria.php">
      <input type="hidden" name="id" id="deleteId" />
      <div class="modal-content">
        <div class="modal-header" style="background: #c43939">          
          <h5 class="modal-title" id="modalDeleteLabel" style="font-weight: bold; color: white;">Ingin Hapus Data Ini?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger" style="background: #c43939">Hapus</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Script Bootstrap dan JQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function setDeleteId(id) {
    document.getElementById('deleteId').value = id;
  }
</script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

</body>
</html>
