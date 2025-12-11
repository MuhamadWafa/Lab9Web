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