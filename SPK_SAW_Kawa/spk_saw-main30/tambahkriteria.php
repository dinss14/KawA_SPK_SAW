<?php
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

require 'config/koneksi.php';

$kriteria = $bobot = $atribut = "";
$kriteriaErr = $bobotErr = $atributErr = "";
$flag = true;

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["kriteria"])) {
        $kriteriaErr = "*Kriteria Belum Diisi";
        $flag = false;
    } else {
        $kriteria = validate($_POST["kriteria"]);
    }

    if (empty($_POST["bobot"])) {
        $bobotErr = "*Bobot Belum Diisi";
        $flag = false;
    } else {
        $bobot = validate($_POST["bobot"]);
        // Validasi untuk float antara 0-1
        if (!is_numeric($bobot)) {
            $bobotErr = "*Bobot harus berupa angka";
            $flag = false;
        } elseif ($bobot < 0 || $bobot > 1) {
            $bobotErr = "*Bobot harus antara 0 dan 1";
            $flag = false;
        }
    }

    if (empty($_POST["atribut"])) {
        $atributErr = "*Atribut Belum Diisi";
        $flag = false;
    } else {
        $atribut = validate($_POST["atribut"]);
    }

    if ($flag) {
        // Cari ID terkecil yang belum dipakai
        $id = 1;
        $query = "SELECT id_kriteria FROM kriteria ORDER BY id_kriteria ASC";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $ids = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $ids[] = (int)$row['id_kriteria'];
            }
            // Cari ID terkecil yang tidak ada di $ids
            while (in_array($id, $ids)) {
                $id++;
            }
        }

        $sql = "INSERT INTO kriteria (id_kriteria, kriteria, bobot, atribut) VALUES ('$id', '$kriteria', '$bobot', '$atribut')";
        $resultInsert = mysqli_query($conn, $sql);

        if ($resultInsert) {
            header("Location: dashboard.php?url=kriteria");
            exit;
        } else {
            echo "Error saat menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Kriteria</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        form { width: 100%; }
        body { font-family: "Verdana"; }
        .error {
            font-size: 11px;
            font-weight: bold;
            color: firebrick;
            margin-bottom: 6px;
        }
        input:invalid {
            border-color: #e74a3b;
        }
    </style>
</head>

<body id="page-top">
    <br>
    <div class="card shadow" style="width: 50%;">
        <div class="card-header m-0 font-weight-bold text-center" style="background-color: #987661; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            Tambah Data Kriteria
        </div>
        <div class="card-body">
            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group cols-sm-6">
                    <label style="color: black">Kriteria</label>
                    <input type="text" name="kriteria" value="<?= htmlspecialchars($kriteria) ?>" class="form-control" style="color: black" required>
                    <span class="error"><?= $kriteriaErr ?></span>
                </div>

                <div class="form-group cols-sm-6">
                    <label style="color: black">Bobot (0-1)</label>
                    <input type="number" step="0.01" min="0" max="1" name="bobot" value="<?= htmlspecialchars($bobot) ?>" class="form-control" style="color: black" required>
                    <span class="error"><?= $bobotErr ?></span>
                </div>

                <div class="form-group cols-sm-6">
                    <label style="color: black">Atribut</label>
                    <select class="form-control" name="atribut" style="color: black" required>
                        <option value="" hidden></option>
                        <option value="keuntungan" <?= $atribut == 'keuntungan' ? 'selected' : '' ?>>Keuntungan</option>
                        <option value="biaya" <?= $atribut == 'biaya' ? 'selected' : '' ?>>Biaya</option>
                    </select>
                    <span class="error"><?= $atributErr ?></span>
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