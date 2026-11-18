<?php
require_once "koneksi.php";

$sql = "SELECT * FROM data_barang ORDER BY id_barang DESC";
$result = mysqli_query($conn, $sql);

function rupiah($angka) {
    return "Rp" . number_format((float)$angka, 0, ",", ".");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang | Praktikum 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="topbar">
        <div>
            <p class="eyebrow">PRAKTIKUM 8 · PHP & MYSQL</p>
            <h1>Data Barang</h1>
            <p class="subtitle">Kelola inventaris barang melalui aplikasi CRUD sederhana.</p>
        </div>
        <a class="btn btn-primary" href="tambah.php">+ Tambah Barang</a>
    </header>

    <?php if (isset($_GET["status"])): ?>
        <div class="alert success">
            <?php
            $messages = [
                "ditambah" => "Data barang berhasil ditambahkan.",
                "diubah" => "Data barang berhasil diperbarui.",
                "dihapus" => "Data barang berhasil dihapus."
            ];
            echo htmlspecialchars($messages[$_GET["status"]] ?? "Proses berhasil.");
            ?>
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Daftar Inventaris</h2>
                <p>Data yang tersimpan pada database latihan1.</p>
            </div>
            <span class="count"><?= mysqli_num_rows($result); ?> barang</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row["gambar"]) && file_exists(__DIR__ . "/" . $row["gambar"])): ?>
                                <img class="product-image" src="<?= htmlspecialchars($row["gambar"]); ?>" alt="<?= htmlspecialchars($row["nama"]); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td class="product-name"><?= htmlspecialchars($row["nama"]); ?></td>
                        <td><span class="category"><?= htmlspecialchars($row["kategori"]); ?></span></td>
                        <td><?= rupiah($row["harga_beli"]); ?></td>
                        <td><?= rupiah($row["harga_jual"]); ?></td>
                        <td><?= (int)$row["stok"]; ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-small btn-edit" href="ubah.php?id=<?= (int)$row["id_barang"]; ?>">Ubah</a>
                                <a class="btn btn-small btn-delete" href="hapus.php?id=<?= (int)$row["id_barang"]; ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="empty">Belum ada data barang. Silakan tambahkan data baru.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <footer>Praktikum 8 — Pemrograman Web OBE</footer>
</div>
</body>
</html>
