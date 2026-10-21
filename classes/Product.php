<?php
require_once 'DatabaseConnection.php';

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getConnection();
        // createTable() dihapus karena tabel sudah dibuat manual di MariaDB
    }

    // CREATE - tambah data
    public function save($name, $price, $stock, $description = '')
    {
        $sql = "INSERT INTO products (name, price, stock, description) 
                VALUES (:name, :price, :stock, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'        => $name,
            ':price'       => $price,
            ':stock'       => $stock,
            ':description' => $description
        ]);
    }

    // READ - lihat semua
    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // READ - lihat satu
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // UPDATE
    public function update($id, $name, $price, $stock, $description = '')
    {
        $sql = "UPDATE products SET 
                    name = :name, 
                    price = :price, 
                    stock = :stock, 
                    description = :description 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $name,
            ':price'       => $price,
            ':stock'       => $stock,
            ':description' => $description
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}