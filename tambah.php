<?php
require_once "koneksi.php";

$errors = [];
$nama = $kategori = "";
$harga_jual = $harga_beli = $stok = "";

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

    $gambar = null;
    if (isset($_FILES["file_gambar"]) && $_FILES["file_gambar"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["file_gambar"];
        $allowed = ["jpg", "jpeg", "png", "webp"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($file["error"] !== UPLOAD_ERR_OK) $errors[] = "Upload gambar gagal.";
        elseif (!in_array($ext, $allowed, true)) $errors[] = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
        elseif ($file["size"] > 2 * 1024 * 1024) $errors[] = "Ukuran gambar maksimal 2 MB.";
        else {
            $newName = uniqid("barang_", true) . "." . $ext;
            if (!move_uploaded_file($file["tmp_name"], __DIR__ . "/gambar/" . $newName)) {
                $errors[] = "Gambar tidak dapat disimpan.";
            } else {
                $gambar = "gambar/" . $newName;
            }
        }
    }

    if (!$errors) {
        $stmt = mysqli_prepare($conn, "INSERT INTO data_barang (kategori, nama, gambar, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssddi", $kategori, $nama, $gambar, $harga_beli, $harga_jual, $stok);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?status=ditambah");
            exit;
        }
        $errors[] = "Data gagal disimpan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container form-container">
    <a class="back-link" href="index.php">← Kembali ke Data Barang</a>
    <section class="form-card">
        <p class="eyebrow">CREATE · DATA BARANG</p>
        <h1>Tambah Barang</h1>
        <p class="subtitle">Lengkapi informasi barang yang akan disimpan.</p>
        <?php if ($errors): ?><div class="alert error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" action="tambah.php" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field full"><label>Nama Barang</label><input type="text" name="nama" maxlength="30" value="<?= htmlspecialchars($nama); ?>" required></div>
                <div class="field"><label>Kategori</label><select name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach (["Komputer", "Elektronik", "Hand Phone"] as $option): ?>
                    <option value="<?= $option; ?>" <?= $kategori === $option ? "selected" : ""; ?>><?= $option; ?></option>
                    <?php endforeach; ?>
                </select></div>
                <div class="field"><label>Stok</label><input type="number" name="stok" min="0" value="<?= htmlspecialchars((string)$stok); ?>" required></div>
                <div class="field"><label>Harga Beli (Rp)</label><input type="number" name="harga_beli" min="0" step="1" value="<?= htmlspecialchars((string)$harga_beli); ?>" required></div>
                <div class="field"><label>Harga Jual (Rp)</label><input type="number" name="harga_jual" min="0" step="1" value="<?= htmlspecialchars((string)$harga_jual); ?>" required></div>
                <div class="field full"><label>Gambar Barang</label><input type="file" name="file_gambar" accept=".jpg,.jpeg,.png,.webp"><small>Format JPG, PNG, WEBP · Maksimal 2 MB.</small></div>
            </div>
            <div class="form-actions"><a class="btn btn-light" href="index.php">Batal</a><button class="btn btn-primary" type="submit">Simpan Barang</button></div>
        </form>
    </section>
</div>
</body>
</html>
