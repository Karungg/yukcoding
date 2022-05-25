<?php

require_once '../config/koneksi.php';
require_once '../models/database.php';
include '../models/barang.php';

$connection = new Database($host , $user, $pass, $database);
$barang = new Barang($connection);

$id = $_POST['id'];
$nama = $connection->conn->real_escape_string($_POST['nama']);
$harga = $connection->conn->real_escape_string($_POST['harga']);
$stok = $connection->conn->real_escape_string($_POST['stok']);

$img = $_FILES['gambar']['name'];
$extensi = explode(".", $_FILES['gambar']['name']);
$gambar = "barang-" . round(microtime(true)) . "." . end($extensi);
$sumber = $_FILES['gambar']['tmp_name'];

if ($img == '') {
    $barang->edit("UPDATE tb_barang SET nama_barang = '$nama', harga_barang = '$harga', stok = '$stok' WHERE id_barang = '$id'");
    echo "<script>
    window.location='?page=barang';
    </script>";
}else{
    $gambarLama = $barang->tampil($id)->fetch_object()->gambar;
    unlink("../assets/img/barang/". $gambarLama);
    $upload = move_uploaded_file($sumber, "../assets/img/barang/" . $gambar);

    if ($upload) {
        $barang->edit("UPDATE tb_barang SET nama_barang = '$nama', harga_barang = '$harga', stok = '$stok', gambar = '$gambar' WHERE id_barang = '$id'");
        echo "<script>
        window.location = '?page=barang';
        </script>";
    }
}
