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