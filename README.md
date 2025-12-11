# Lab9Web
# Muhamad Wafa Mufida Zulfi
# 312410334
# TI.24.A4
# Agung Nugroho, S.Kom., M.Kom.

Proyek ini berfokus pada modernisasi dan restrukturisasi aplikasi berbasis PHP yang ada, mentransformasikannya dari kode yang tersebar dan tidak terkelola menjadi arsitektur yang terpusat dan tersusun rapi.

### struktur proyek
<img width="513" height="743" alt="Cuplikan layar 2025-12-11 150002" src="https://github.com/user-attachments/assets/e8141bf1-9036-446e-a392-0809cee45cd5" />

## 1. koneksi database

Tahap inisiasi dari modularisasi melibatkan penyusunan ulang hierarki file ke dalam struktur direktori yang terorganisir secara semantik: config, views, modules, dan assets.

Langkah krusialnya adalah pemusatan pengaturan koneksi. Berkas config/database.php bertindak sebagai repositori sentral untuk menyimpan parameter koneksi database (seperti host, user, pass, dan db). Berkas ini juga bertanggung jawab untuk memastikan validitas dan keberhasilan koneksi ke basis data latihan2 yang ditargetkan.

Selanjutnya, router utama aplikasi, yang diwakili oleh index.php, akan memuat dan menginisialisasi berkas konfigurasi koneksi ini pada permulaan setiap eksekusi program.
### database.php

```python
<?php
// config/database.php
$host = "localhost"; 
$user = "root";    
$pass = "";      
$db = "latihan2";  // Nama database yang sudah dibuat

$conn = mysqli_connect($host, $user, $pass, $db); // Melakukan koneksi

if ($conn == false)
{
    // Hentikan eksekusi jika koneksi gagal
    die("Koneksi ke server gagal: " . mysqli_connect_error());
}
?>
```
## 2. Roter Utama (index.php) otentikasi

Berkas index.php berfungsi sebagai Pengendali Utama (Main Controller) atau Pusat Perutean (Central Router) dari aplikasi. Peran utamanya adalah menganalisis parameter URL (misalnya, ?page=...) untuk menentukan dan memuat modul atau halaman yang relevan.

Selain fungsi perutean, pada implementasi ini, index.php juga bertindak sebagai Penjaga Akses (Access Guard). Mekanisme ini secara sistematis memverifikasi status otentikasi pengguna melalui variabel sesi ($_SESSION['is_login']).

Jika pengguna yang belum terotentikasi (unauthenticated) berupaya mengakses sumber daya atau halaman yang dilindungi (seperti data_barang/list), router akan mencegah akses tersebut dan segera mengarahkan ulang (redirect) pengguna ke halaman login (auth/login), sehingga integritas dan kerahasiaan data tetap terjamin.

 ### index.php
```python
 <?php
// index.php (Router Utama, dengan Auth)

// --- PERBAIKAN PRIORITAS: AKTIFKAN DEBUGGING ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ------------------------------------------------

// Aktifkan session harus paling atas
session_start(); 

require_once 'config/database.php'; // Koneksi database dimuat

// ... (lanjutkan dengan kode Anda yang lain)
// index.php (Router Utama, dengan Auth)

// Aktifkan session harus paling atas
session_start(); 

require_once 'config/database.php'; // Koneksi database dimuat

// Definisikan halaman yang bisa diakses publik (tanpa login)
$public_pages = [
    'auth/login', 
    'auth/logout' 
];

// 1. Logika Routing
$page = $_GET['page'] ?? 'data_barang/list'; // Default ke list data

// Bersihkan input
$page = preg_replace('/[^a-zA-Z0-9_\/]/', '', $page);
$module_path = 'modules/' . $page . '.php';

// 2. Pemeriksaan Akses (Guard)
$is_public = in_array($page, $public_pages);

if (!$is_public && !isset($_SESSION['is_login'])) {
    // Jika tidak login DAN mencoba mengakses halaman privat, paksa ke login
    header('location: index.php?page=auth/login');
    exit;
} else if ($page == 'auth/login' && isset($_SESSION['is_login'])) {
    // Jika sudah login tapi mengakses halaman login, arahkan ke home
    header('location: index.php');
    exit;
}

// 3. Load Module
if (file_exists($module_path)) {
    // Set Title
    $title = ucwords(str_replace(['/', '_'], ' ', $page)); 
    
    require 'views/header.php';
    
    // Muat konten module
    require $module_path;
    
    require 'views/footer.php';
} else {
    // Halaman 404 sederhana
    require 'views/header.php';
    echo '<h2 style="color: #dc3545; text-align: center;">404 Not Found</h2>';
    echo '<p style="text-align: center;">Halaman modul yang Anda cari tidak ditemukan: ' . htmlspecialchars($module_path) . '</p>';
    require 'views/footer.php';
}
?>
```
### 3. komponen tampilan views

Komponen Views yang terdiri dari views/header.php dan views/footer.php berfungsi sebagai kerangka tampilan (template) yang diterapkan secara universal di seluruh modul aplikasi.

header.php: Berkas ini bertanggung jawab untuk menginisiasi kerangka dokumen HTML, termasuk tag <head>, penyertaan berkas CSS, struktur pembuka HTML, elemen navigasi visual tingkat atas (disebut 'kotak biru' dalam deskripsi), serta logika antarmuka untuk fitur Otentikasi (link Login/Logout).

footer.php: Berkas ini bertugas untuk menyelesaikan struktur HTML dengan menutup tag yang terbuka dan menyediakan ruang untuk elemen tambahan seperti informasi hak cipta (copyright).

Pemisahan tanggung jawab ini secara efektif menjamin tampilan dan nuansa (look and feel) yang seragam dan memfasilitasi kemudahan pembaruan pada seluruh antarmuka pengguna (UI) aplikasi.

### header.php
```python
<?php
// views/header.php (Sudah dimodifikasi untuk Auth dan penempatan pesan sambutan)

// Pastikan session sudah dimulai di index.php
$is_logged_in = isset($_SESSION['is_login']) && $_SESSION['is_login'];
$title = $title ?? 'Aplikasi Data Barang';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title); ?></title>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>Data Barang (Modular)</h1>
            </div>
            
            <?php if ($is_logged_in): ?>
            <div class="welcome-message">
                Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>!
            </div>
            <?php endif; ?>

            <nav style="text-align: center; margin-bottom: 20px;">
                <?php if ($is_logged_in): ?>
                    <a href="index.php?page=data_barang/list" class="btn-tambah" style="margin-right: 10px;">Lihat Data</a>
                    <a href="index.php?page=data_barang/add" class="btn-tambah">Tambah Data</a>
                    <a href="index.php?page=auth/logout" class="btn-tambah" style="background-color: var(--danger-color);">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=auth/login" class="btn-tambah">Login Admin</a>
                <?php endif; ?>
            </nav>
        </header>
        <div class="main">
```
### footer.php
```python
<?php
// views/footer.php
?>
        </div>
        <footer>
            <p style="text-align: center; margin-top: 20px;">&copy; 2025, Modularisasi PHP - Lab 9</p>
        </footer>
    </div>
</body>
</html>
```

## 4. Modul Data Barang (modules/data_barang/)
Modul-modul ini adalah tempat penyimpanan utama untuk semua operasi dasar pengelolaan data, yaitu CRUD (Create, Read, Update, Delete).

Sebelumnya: Logika-logika ini tersebar di banyak file terpisah (index.php, tambah.php, ubah.php, dan hapus.php).

Sekarang: Semua operasi tersebut telah dikonsolidasikan dan dipusatkan di dalam direktori modules/ untuk membuat kode lebih rapi dan terorganisir.

Setiap modul dapat menjalankan tugasnya dengan lancar karena ia berbagi koneksi database yang sama ($conn). Koneksi penting ini sudah disiapkan dan dimuat secara global oleh router utama (index.php).

Selain itu, semua tautan (link) yang mengaktifkan operasi modul kini menggunakan sistem alamat yang terpusat (skema routing):

Contoh: index.php?page=modul/aksi

Ini membuat alur aplikasi menjadi teratur dan mudah diprediksi.

### list.php
```python
<?php
// modules/data_barang/list.php (Menampilkan data)

$sql  = 'SELECT * FROM data_barang ORDER BY id_barang DESC'; 
$result  = mysqli_query($conn, $sql); 
?>

<table>
    <tr>
        <th>Gambar</th>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th>Harga Jual</th>
        <th>Harga Beli</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    <?php if($result && mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_array($result)): ?>
    <tr>
        <td>
            <?php if ($row['gambar']): ?>
                <img src="assets/gambar/<?= htmlspecialchars($row['gambar']);?>" alt="<?= htmlspecialchars($row['nama']);?>">
            <?php else: ?>
                Tidak Ada Gambar
            <?php endif; ?>
        </td> 
        
        <td><?= htmlspecialchars($row['nama']);?></td>
        <td><?= htmlspecialchars($row['kategori']);?></td>
        <td><?= htmlspecialchars($row['harga_jual']);?></td>
        <td><?= htmlspecialchars($row['harga_beli']);?></td>
        <td><?= htmlspecialchars($row['stok']);?></td>
        <td>
            <a href="index.php?page=data_barang/edit&id=<?= $row['id_barang'];?>">Ubah</a> 
            
            <a href="index.php?page=data_barang/delete&id=<?= $row['id_barang'];?>" onclick="return confirm('Yakin akan menghapus data ini?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; else: ?>
    <tr>
        <td colspan="7">Belum ada data di database.</td>
    </tr>
    <?php endif; ?>
</table>
```

### add.php
```python
<?php
// modules/data_barang/add.php (Menambah data)

function redirect_list() {
    header('location: index.php?page=data_barang/list'); 
    exit;
}

if (isset($_POST['submit']))
{
    // ... (Logika pengambilan data form) ...
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $file_gambar = $_FILES['file_gambar'];
    $gambar  = null;

    // Proses upload gambar
    if ($file_gambar ['error'] == 0) 
    {
        $filename  = str_replace(' ', '_', $file_gambar ['name']);
        // Path upload disesuaikan ke folder assets/gambar/ dari root project
        $destination = dirname(dirname(dirname(__FILE__))) . '/assets/gambar/' . $filename; 

        if(move_uploaded_file($file_gambar ['tmp_name'], $destination)) 
        {
            $gambar = $filename;
        }
    }
    
    // Query INSERT
    $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar) 
            VALUES ('{$nama}', '{$kategori}', '{$harga_jual}', '{$harga_beli}', '{$stok}', '{$gambar}')";
    
    $result  = mysqli_query($conn, $sql);
    
    if ($result) {
        redirect_list(); 
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<h1 style="text-align: center;">Tambah Barang</h1>
<form method="post" action="index.php?page=data_barang/add" enctype="multipart/form-data">
    <div class="input">
        <label>Nama Barang</label>
        <input type="text" name="nama" required/>
    </div>
    <div class="input">
        <label>Kategori</label>
        <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Komputer">Komputer</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Hand Phone">Hand Phone</option>
        </select>
    </div>
    <div class="input">
        <label>Harga Jual</label>
        <input type="number" name="harga_jual" required/>
    </div>
    <div class="input">
        <label>Harga Beli</label>
        <input type="number" name="harga_beli" required/>
    </div>
    <div class="input">
        <label>Stok</label>
        <input type="number" name="stok" required/>
    </div>
    <div class="input">
        <label>File Gambar</label>
        <input type="file" name="file_gambar" />
    </div>
    <div class="submit">
        <input type="submit" name="submit" value="Simpan" />
    </div>
</form>
```
### edit.php
```python
<?php
// modules/data_barang/edit.php (Mengubah data)

function is_select($val, $var) {
    if ($var == $val) return 'selected="selected"';
    return '';
}

function redirect_list() {
    header('location: index.php?page=data_barang/list'); 
    exit;
}

// 1. Logika Pemrosesan Form UPDATE saat di-submit
if (isset($_POST['submit']))
{
    // ... (Logika pengambilan data form dan upload gambar) ...
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $file_gambar = $_FILES['file_gambar'];
    $gambar  = null;

    // Proses upload gambar baru (jika ada)
    if ($file_gambar ['error'] == 0)
    {
        $filename  = str_replace(' ', '_', $file_gambar['name']);
        $destination = dirname(dirname(dirname(__FILE__))) . '/assets/gambar/' . $filename;

        if (move_uploaded_file($file_gambar['tmp_name'], $destination))
        {
            $gambar = $filename; 
        }
    }
    
    // Query UPDATE
    $sql = 'UPDATE data_barang SET ';
    $sql.= "nama = '{$nama}', kategori = '{$kategori}', ";
    $sql.= "harga_jual = '{$harga_jual}', harga_beli = '{$harga_beli}', stok = '{$stok}' ";
    
    if (!empty($gambar)) 
    {
        $sql.=", gambar = '{$gambar}' ";
    }
    
    $sql.= "WHERE id_barang = '{$id}'"; 
    
    $result  = mysqli_query($conn, $sql);
    
    if ($result) {
        redirect_list();
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

// 2. Logika Pengambilan Data untuk ditampilkan di form
if (!isset($_GET['id'])) {
    redirect_list();
}
$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM data_barang WHERE id_barang = '{$id}'";
$result  = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die('Error: Data tidak ditemukan.');
}
$data  = mysqli_fetch_array($result);

?>
<h1 style="text-align: center;">Ubah Barang</h1>
<form method="post" action="index.php?page=data_barang/edit" enctype="multipart/form-data">
    <div class="input">
        <label>Nama Barang</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']);?>" required/>
    </div>
    <div class="input">
        <label>Kategori</label>
        <select name="kategori" required>
            <option <?php echo is_select($data['kategori'], 'Komputer'); ?> value="Komputer">Komputer</option>
            <option <?php echo is_select($data['kategori'], 'Elektronik');?> value="Elektronik">Elektronik</option>
            <option <?php echo is_select($data['kategori'], 'Hand Phone'); ?> value="Hand Phone">Hand Phone</option>
        </select>
    </div>
    <div class="input">
        <label>Harga Jual</label>
        <input type="number" name="harga_jual" value="<?php echo htmlspecialchars($data['harga_jual']);?>" required/>
    </div>
    <div class="input">
        <label>Harga Beli</label>
        <input type="number" name="harga_beli" value="<?php echo htmlspecialchars($data['harga_beli']);?>" required/>
    </div>
    <div class="input">
        <label>Stok</label>
        <input type="number" name="stok" value="<?php echo htmlspecialchars($data['stok']);?>" required/>
    </div>
    <div class="input">
        <label>File Gambar (Kosongkan jika tidak diubah)</label>
        <input type="file" name="file_gambar" />
        <?php if ($data['gambar']): ?>
            <p>Gambar Saat Ini: 
                <img src="assets/gambar/<?php echo htmlspecialchars($data['gambar']);?>" style="max-width: 100px; max-height: 100px; display: block; margin-top: 10px;">
            </p>
        <?php endif; ?>
    </div>
    <div class="submit">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id_barang']);?>" />
        <input type="submit" name="submit" value="Simpan Perubahan" />
    </div>
</form>
```
### delete.php
```python
<?php
// modules/data_barang/delete.php (Menghapus data)

function redirect_list() {
    header('location: index.php?page=data_barang/list'); 
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM data_barang WHERE id_barang = '{$id}'"; // Query DELETE
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Gagal menghapus data: " . mysqli_error($conn));
    }
}

redirect_list();
?>
```
## 5. modul otentikasi lanjutan ```modules/auth/```
