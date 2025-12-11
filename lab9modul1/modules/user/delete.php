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