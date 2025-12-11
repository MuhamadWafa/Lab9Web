<?php
// modules/auth/login.php

// Cek jika user sudah login, arahkan ke halaman utama
if (isset($_SESSION['is_login'])) {
    header('location: index.php');
    exit;
}

$error = '';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Password tidak perlu di-escape saat ini

    // Query untuk mencari user
    $sql = "SELECT * FROM user WHERE username = '{$username}' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Cek password. Karena di DB tidak di-hash, kita cek teks biasa (TIDAK AMAN untuk real project)
        // Untuk tujuan praktikum, kita anggap password di DB adalah 'admin123'
        // Jika Anda menggunakan hash, ganti dengan: if (password_verify($password, $user['password']))
        if ($password === '12345' && $user['username'] === 'zaki') { 
            // Jika login berhasil
            session_start();
            $_SESSION['is_login'] = true; // Tandai sesi login berhasil
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];

            // Redirect ke halaman utama
            header('location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Username tidak ditemukan.';
    }
}
?>

<h1 style="text-align: center;">Login Administrator</h1>

<form method="post" action="index.php?page=auth/login">
    <?php if ($error): ?>
        <p style="color: var(--danger-color); text-align: center; margin-bottom: 20px; border: 1px solid var(--danger-color); padding: 10px; border-radius: 5px;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <div class="input">
        <label>Username</label>
        <input type="text" name="username" required/>
    </div>
    <div class="input">
        <label>Password</label>
        <input type="password" name="password" required/>
    </div>
    <div class="submit">
        <input type="submit" name="submit" value="Login" />
    </div>
</form>
