<?php
if(!isset($_SESSION['username']))
{
?>
<script type="text/javascript">
alert('Anda Belum Login');
window.location = 'index.php';
</script>
<?php
exit(); // tambahkan exit agar kode PHP berhenti setelah redirect
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>TAMBAH DATA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style>
form {
    width: 100%;
}
body {
    font-family: "Verdana";
}
.error {
    font-size: 11px;
    font-weight: bold;
    position: relative;
    left: 10px;
    margin-bottom: 6px;
    color: firebrick;
}
</style>

<?php
require 'config/koneksi.php';

$namaErr = NULL;
$nama = NULL;
$flag = true;

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (empty($_POST["nama"])) {
        $namaErr = "*Nama Belum Diisi";
        $flag = false;
    } else {
        $nama = validate($_POST['nama']);
    }

    if ($flag) {
        $sql = "INSERT INTO alternatif(nama_alternatif) VALUES('$nama')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("Location: dashboard.php?url=alternatif");
            exit();
        }
    }
}
?>

<body id="page-top">
<br>
<div class="card shadow" style="width: 50%;">
    <div class="card-header m-0 font-weight-bold" style="text-align:center; background-color: #987661; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
        Tambah Data Alternatif
    </div>
    <div class="card-body">
        <form method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group cols-sm-6">
                <label style="color: black">Nama Cafe</label>
                <input type="text" name="nama" value="<?= $nama ?>" class="form-control" style="color: black">
                <span class="error"><?= $namaErr ?></span>
            </div>
            <div class="form-group cols-sm-6">
                <button type="submit" class="btn btn-secondary btn-icon-split" style="background: #88603C" name="submit">
                    <span class="icon text-white-50">
                        <i class="fas fa-user-check"></i>
                    </span>
                    <span class="text">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>
</body>

</html>
