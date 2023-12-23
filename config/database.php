<?php

class Database
{
    /**
     * Menginisialisasi properti 
     * host, db_name, user dan password yang akan digunakan
     * untuk menangani koneksi ke database
     */
    private $host = "localhost";
    private $db_name = "restapi-php";
    private $user = "pratama";
    private $password = "localhost";

    /** Meninisialisasi properti koneksi */
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            /** Membuat objek PDO untuk koneksi ke database */
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->user, $this->password);

            /** Mengatur pengaturan karakter ke UTF-8 */
            $this->conn->exec("set names utf8");

            /** Tampilkan pesan jika berhasi maupun gagal terkoneksi ke database */
            // if ($this->conn) {
            //     echo "Connected to db";
            // } else {
            //     echo "Failed to connect";
            // }
        } catch (PDOException $exception) {
            /** Menangkap dan menampilkan pesan kesalahan jika terjadi masalah koneksi */
            echo "Connection error :" . $exception->getMessage();
        }
        return $this->conn;
    }
}

// $database = new Database();
// $database->getConnection();
