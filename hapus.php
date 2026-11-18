<?php
require_once "koneksi.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit; }

$stmt = mysqli_prepare($conn, "SELECT gambar FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if ($data) {
    $delete = mysqli_prepare($conn, "DELETE FROM data_barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($delete, "i", $id);
    if (mysqli_stmt_execute($delete) && !empty($data["gambar"]) && is_file(__DIR__ . "/" . $data["gambar"])) {
        unlink(__DIR__ . "/" . $data["gambar"]);
    }
}
header("Location: index.php?status=dihapus");
exit;
