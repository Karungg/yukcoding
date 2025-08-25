<?php

require_once '../config/koneksi.php';
require_once '../models/database.php';
include '../models/barang.php';

$connection = new Database($host , $user, $pass, $database);
$barang = new Barang($connection);

$fileName = "excel-barang-(".date('d-m-Y').")";
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename= $fileName.xls");
?>

<table border="1px">
    <tr>
        <th>No.</th>
        <th>Nama Barang</th>
        <th>Harga Barang</th>
        <th>Stok</th>
    </tr>
    <?php $no = 1;
    $tampil = $barang->tampil();
    while ($data = $tampil->fetch_object() ) : ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $data->nama_barang; ?></td>
        <td><?= $data->harga_barang; ?></td>
        <td><?= $data->stok; ?></td>
    </tr>
    <?php endwhile; ?>
</table>