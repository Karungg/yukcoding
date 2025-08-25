<?php 
include '../models/barang.php';
include 'partials/modal-barang.php';
$barang = new Barang($connection);

if (@$_POST['tambah']) {
    $nama = $connection->conn->real_escape_string($_POST['nama']);
    $harga = $connection->conn->real_escape_string($_POST['harga']);
    $stok = $connection->conn->real_escape_string($_POST['stok']);

    $extensi = explode('.', $_FILES['gambar']['name']);
    $gambar = "barang-" . round(microtime(true)) . "." . end($extensi);
    $sumber = $_FILES['gambar']['tmp_name'];
    $upload = move_uploaded_file($sumber, "../assets/img/barang/" . $gambar);

    if ($upload) {
        $barang->tambah($nama, $harga, $stok, $gambar);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }else{
        echo "<script>
        alert('Upload gagal!');
        </script>";
    }

}

if (@$_GET['action'] == '') {

?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Barang</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
         <i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <button class="btn btn-success mb-2" type="button" data-bs-toggle="modal" data-bs-target="#tambah">Tambah Data</button>
            <a href="../report/export_excel.php" class="text-decoration-none" target="_blank">
            <button class="btn btn-outline-dark mb-2" type="button" name="print"><i class="fas fa-print"></i> Export Excel</button>
            </a>
            <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
                        <th>Stok</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <?php
                $no = 1;
                $tampil = $barang->tampil();
                while ($data = $tampil->fetch_object()) : ?>
                <tbody>
                    <tr>
                        <td align="center"><?= $no++. "."; ?></td>
                        <td><?= $data->nama_barang; ?></td>
                        <td><?= $data->harga_barang; ?></td>
                        <td><?= $data->stok; ?></td>
                        <td align="center"><img src="../assets/img/barang/<?= $data->gambar; ?>" alt="" width="70"></td>
                        <td align="center">
                            <a href="" class="text-decoration-none" id="edit" data-bs-toggle="modal" data-bs-target="#modal-edit" data-id="<?= $data->id_barang; ?>" data-nama="<?= $data->nama_barang; ?>" data-harga="<?= $data->harga_barang; ?>"  data-stok="<?= $data->stok ?>" data-gambar="<?= $data->gambar; ?>">
                            <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                            </a>
                            <a href="?page=barang&action=delete&id=<?= $data->id_barang; ?>" class="text-decoration-none" onclick="return confirm('Hapus?')">
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </a>
                        </td>
                    </tr>
                </tbody>
                <?php endwhile; ?>
            </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on("click", "#edit", function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var harga = $(this).data('harga');
            var stok = $(this).data('stok');
            var gambar = $(this).data('gambar');

            $("#modal-edit #id").val(id);
            $("#modal-edit #nama").val(nama);
            $("#modal-edit #harga").val(harga);
            $("#modal-edit #stok").val(stok);
            $("#modal-edit #gambar").attr("src", "../assets/img/barang/" + gambar);
        });

        $(document).ready(function(e) {
            $("#form").on("submit", (function(e){
                e.preventDefault();
                $.ajax({
                    url : '../models/edit-barang.php',
                    type : 'POST',
                    data : new FormData(this),
                    contentType : false,
                    cache : false,
                    processData : false,
                    success : function(msg) {
                        $('.table').html(msg);
                    }
                });
            }));
        })
    </script>
    <?php  
    }elseif($_GET['action'] == 'delete'){
        $gambarLama = $barang->tampil($_GET['id'])->fetch_object()->gambar;
        unlink("../assets/img/barang/". $gambarLama);

        $barang->delete($_GET['id']);
        echo "<script>
        alert('Data berhasil dihapus');
        window.location = '?page=barang';
        </script>";
    }
    ?>