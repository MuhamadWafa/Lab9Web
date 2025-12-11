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
