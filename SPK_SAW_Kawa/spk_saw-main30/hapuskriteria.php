<?php
require 'config/koneksi.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    // Hapus data
    $stmt = $conn->prepare("DELETE FROM kriteria WHERE id_kriteria = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();

    if ($success) {
        // Reindex id_kriteria agar urut dari 1 tanpa ada yang terlewat
        // 1. Ambil semua data sesuai urutan lama
        $result = $conn->query("SELECT id_kriteria FROM kriteria ORDER BY id_kriteria ASC");

        $newId = 1;
        while ($row = $result->fetch_assoc()) {
            $oldId = $row['id_kriteria'];
            if ($oldId != $newId) {
                // Update id_kriteria menjadi urutan baru
                $stmtUpdate = $conn->prepare("UPDATE kriteria SET id_kriteria = ? WHERE id_kriteria = ?");
                $stmtUpdate->bind_param("ii", $newId, $oldId);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
            $newId++;
        }

        header("Location: dashboard.php?url=kriteria");
        exit;
    } else {
        header("Location: dashboard.php?url=kriteria&status=gagal");
        exit;
    }
} else {
    header("Location: dashboard.php?url=kriteria&status=invalid");
    exit;
}
?>
