# Praktikum 8 – PHP dan Database MySQL

**Mata Kuliah:** Pemrograman Web OBE  
**Topik:** Implementasi CRUD (Create, Read, Update, Delete) menggunakan PHP dan MySQL.

## 1. Tujuan Praktikum
1. Memahami konsep dasar database.
2. Memahami konsep dasar CRUD menggunakan PHP.
3. Membuat program CRUD sederhana menggunakan PHP.

## 2. Persiapan
Perangkat lunak yang digunakan:
- XAMPP (Apache dan MySQL)
- Visual Studio Code
- Browser
- Git dan akun GitHub

## 3. Menjalankan Project
1. Salin folder `lab8_php_database` ke direktori `C:\xampp\htdocs\`.
2. Buka XAMPP Control Panel, lalu jalankan Apache dan MySQL.
3. Buka `http://localhost/phpmyadmin/`.
4. Pilih tab **Import**, lalu impor file `database.sql` dari folder project. Alternatifnya, buka tab SQL dan jalankan isi file tersebut.
5. Buka `http://localhost/lab8_php_database/` di browser.

## 4. Pembuatan Database dan Tabel
Database yang digunakan bernama `latihan1`, sedangkan tabelnya bernama `data_barang`. Tabel menyimpan ID, kategori, nama barang, gambar, harga beli, harga jual, dan stok.

**Screenshot:**  
Simpan bukti database dan struktur tabel pada `screenshots/01-database.png`.

## 5. Penambahan Data Awal
Tiga data awal dimasukkan ke tabel `data_barang`, yaitu HP Samsung Android, HP Xiaomi Android, dan HP OPPO Android. Ketiganya menggunakan kategori Elektronik dan stok awal 5.

**Screenshot:**  
Simpan bukti isi tabel pada `screenshots/02-data-awal.png`.

## 6. Koneksi Database
File `koneksi.php` digunakan untuk menghubungkan aplikasi PHP dengan database MySQL `latihan1`. Koneksi menggunakan fungsi `mysqli_connect()`.

**Screenshot:**  
Simpan bukti aplikasi berhasil terhubung ke database atau halaman aplikasi yang berhasil menampilkan data pada `screenshots/03-koneksi.png`.

## 7. Menampilkan Data (Read)
File `index.php` menjalankan query `SELECT` untuk mengambil seluruh data barang dan menampilkannya dalam tabel. Halaman ini juga menyediakan tombol untuk menambah, mengubah, dan menghapus data.

**Screenshot:**  
Simpan tampilan daftar barang pada `screenshots/04-read.png`.

## 8. Menambah Data (Create)
File `tambah.php` menampilkan formulir input nama barang, kategori, harga jual, harga beli, stok, dan gambar. Data disimpan ke database menggunakan query `INSERT`.

**Screenshot:**  
- Form tambah barang: `screenshots/05-form-create.png`
- Hasil data setelah disimpan: `screenshots/06-hasil-create.png`

## 9. Mengubah Data (Update)
File `ubah.php` mengambil data berdasarkan `id_barang`, menampilkan data tersebut pada formulir, kemudian menyimpan perubahan menggunakan query `UPDATE`. Gambar dapat diganti atau dibiarkan tetap menggunakan gambar sebelumnya.

**Screenshot:**  
- Form ubah barang: `screenshots/07-form-update.png`
- Hasil setelah diperbarui: `screenshots/08-hasil-update.png`

## 10. Menghapus Data (Delete)
File `hapus.php` menghapus data berdasarkan `id_barang` menggunakan query `DELETE`. Sebelum penghapusan, aplikasi menampilkan konfirmasi melalui browser.

**Screenshot:**  
- Konfirmasi hapus: `screenshots/09-konfirmasi-delete.png`
- Daftar setelah data dihapus: `screenshots/10-hasil-delete.png`

## 11. Hasil Akhir
Aplikasi CRUD Data Barang berhasil dibuat dengan PHP dan MySQL. Aplikasi dapat menampilkan, menambahkan, mengubah, dan menghapus data barang, termasuk mengunggah gambar barang.

**Screenshot:**  
Simpan tampilan akhir aplikasi pada `screenshots/11-hasil-akhir.png`.

## 12. Kesimpulan
Melalui praktikum ini, konsep dasar database MySQL dan penerapan CRUD menggunakan PHP dapat dipahami. Koneksi PHP-MySQL memungkinkan aplikasi mengelola data secara dinamis melalui operasi Create, Read, Update, dan Delete.

## 13. Repository
Repository GitHub: **[Masukkan URL repository Lab8Web di sini]**

---

## Catatan Pengumpulan
- [ ] Pastikan Apache dan MySQL berjalan.
- [ ] Pastikan semua fitur CRUD dapat digunakan.
- [ ] Lengkapi seluruh screenshot pada folder `screenshots`.
- [ ] Ganti bagian URL repository dengan tautan GitHub milik sendiri.
- [ ] Commit dan push seluruh project ke repository bernama `Lab8Web`.
- [ ] Kirim URL repository ke e-learning eCampus.
