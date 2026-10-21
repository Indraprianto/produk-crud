<?php
class DatabaseConnection
{
    private static $connection = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=localhost;dbname=produk_db;charset=utf8mb4";
                $username = "root";
                $password = "satu";

                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die("Koneksi gagal: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}