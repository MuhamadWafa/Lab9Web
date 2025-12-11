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