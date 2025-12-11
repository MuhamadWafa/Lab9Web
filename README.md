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

C:\xampp\htdocs\lab9modul1\configh\database.php
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
