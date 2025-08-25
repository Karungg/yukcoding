<?php

class Barang
{
    private $mysqli;

    function __construct($conn)
    {
        $this->mysqli = $conn;        
    }

    public function tampil($id = null)
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * FROM tb_barang";
        if ($id != null) {
            $sql .= " WHERE id_barang = $id";
        }
        $query = $db->query($sql) or die($db->error);
        return $query;

    }

    public function tambah($nama, $harga, $stok, $gambar)
    {
	    
        $db = $this->mysqli->conn;
        $db->query("INSERT INTO tb_barang VALUES ('', '$nama', '$harga', '$stok', '$gambar')") or die($db->error);
    }

    public function edit($sql)
    {
        $db = $this->mysqli->conn;
        $db->query($sql) or die($db->error);
    }

    public function delete($id)
    {
        $db = $this->mysqli->conn;
        $db->query("DELETE FROM tb_barang WHERE id_barang = '$id'") or die($db->error);
    }

    public function __destruct()
    {
        $db = $this->mysqli->conn;
        $db->close();
    }

}