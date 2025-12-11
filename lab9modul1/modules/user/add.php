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