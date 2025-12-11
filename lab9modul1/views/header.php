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