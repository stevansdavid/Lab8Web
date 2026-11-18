<?php
require_once "koneksi.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit; }

$stmt = mysqli_prepare($conn, "SELECT * FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
if (!$data) { die("Data barang tidak ditemukan."); }

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $harga_jual = $_POST["harga_jual"] ?? "";
    $harga_beli = $_POST["harga_beli"] ?? "";
    $stok = $_POST["stok"] ?? "";

    if ($nama === "" || strlen($nama) > 30) $errors[] = "Nama barang wajib diisi (maksimal 30 karakter).";
    if ($kategori === "" || strlen($kategori) > 30) $errors[] = "Kategori wajib dipilih.";
    if (!is_numeric($harga_jual) || $harga_jual < 0) $errors[] = "Harga jual harus berupa angka tidak negatif.";
    if (!is_numeric($harga_beli) || $harga_beli < 0) $errors[] = "Harga beli harus berupa angka tidak negatif.";
    if (filter_var($stok, FILTER_VALIDATE_INT) === false || $stok < 0) $errors[] = "Stok harus berupa bilangan bulat tidak negatif.";

    $gambar = $data["gambar"];
    if (isset($_FILES["file_gambar"]) && $_FILES["file_gambar"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["file_gambar"];
        $allowed = ["jpg", "jpeg", "png", "webp"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($file["error"] !== UPLOAD_ERR_OK) $errors[] = "Upload gambar gagal.";
        elseif (!in_array($ext, $allowed, true)) $errors[] = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
        elseif ($file["size"] > 2 * 1024 * 1024) $errors[] = "Ukuran gambar maksimal 2 MB.";
        else {
            $newName = uniqid("barang_", true) . "." . $ext;
            if (move_uploaded_file($file["tmp_name"], __DIR__ . "/gambar/" . $newName)) {
                $gambar = "gambar/" . $newName;
                if (!empty($data["gambar"]) && is_file(__DIR__ . "/" . $data["gambar"])) unlink(__DIR__ . "/" . $data["gambar"]);
            } else $errors[] = "Gambar tidak dapat disimpan.";
        }
    }

    if (!$errors) {
        $stmt = mysqli_prepare($conn, "UPDATE data_barang SET nama=?, kategori=?, harga_jual=?, harga_beli=?, stok=?, gambar=? WHERE id_barang=?");
        mysqli_stmt_bind_param($stmt, "ssddisi", $nama, $kategori, $harga_jual, $harga_beli, $stok, $gambar, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?status=diubah");
            exit;
        }
        $errors[] = "Data gagal diperbarui: " . mysqli_error($conn);
    }
    $data = array_merge($data, ["nama"=>$nama, "kategori"=>$kategori, "harga_jual"=>$harga_jual, "harga_beli"=>$harga_beli, "stok"=>$stok, "gambar"=>$gambar]);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Barang</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container form-container">
    <a class="back-link" href="index.php">← Kembali ke Data Barang</a>
    <section class="form-card">
        <p class="eyebrow">UPDATE · DATA BARANG</p>
        <h1>Ubah Barang</h1>
        <p class="subtitle">Perbarui informasi barang yang dipilih.</p>
        <?php if ($errors): ?><div class="alert error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" action="ubah.php?id=<?= (int)$id; ?>" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field full"><label>Nama Barang</label><input type="text" name="nama" maxlength="30" value="<?= htmlspecialchars($data["nama"]); ?>" required></div>
                <div class="field"><label>Kategori</label><select name="kategori" required>
                    <?php foreach (["Komputer", "Elektronik", "Hand Phone"] as $option): ?>
                    <option value="<?= $option; ?>" <?= $data["kategori"] === $option ? "selected" : ""; ?>><?= $option; ?></option>
                    <?php endforeach; ?>
                </select></div>
                <div class="field"><label>Stok</label><input type="number" name="stok" min="0" value="<?= (int)$data["stok"]; ?>" required></div>
                <div class="field"><label>Harga Beli (Rp)</label><input type="number" name="harga_beli" min="0" value="<?= htmlspecialchars($data["harga_beli"]); ?>" required></div>
                <div class="field"><label>Harga Jual (Rp)</label><input type="number" name="harga_jual" min="0" value="<?= htmlspecialchars($data["harga_jual"]); ?>" required></div>
                <div class="field full">
                    <label>Gambar Barang</label>
                    <?php if (!empty($data["gambar"]) && file_exists(__DIR__ . "/" . $data["gambar"])): ?><img class="preview-image" src="<?= htmlspecialchars($data["gambar"]); ?>" alt="Gambar saat ini"><?php endif; ?>
                    <input type="file" name="file_gambar" accept=".jpg,.jpeg,.png,.webp"><small>Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.</small>
                </div>
            </div>
            <div class="form-actions"><a class="btn btn-light" href="index.php">Batal</a><button class="btn btn-primary" type="submit">Simpan Perubahan</button></div>
        </form>
    </section>
</div>
</body>
</html>
