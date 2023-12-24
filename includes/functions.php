<?php

class Functions

{
    /** Membiat object private $conn */
    private $conn;

    /**
     * Membuat method constructor yang akan dipanggil secara otomatis
     * dimana method ini menerima satu parameter
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }


    /**
     * Method create untuk menambahkan data baru ke database
     */
    public function create($name, $description, $price)
    {
        /**  
         * Query untuk menambahkan data baru ke table products
        */
        $query = "INSERT INTO products SET name=:name, description=:description, price=:price";

        /**
         * Menyiapkan statement SQL untuk di eksekusi nantinya.
         */
        $stmt = $this->conn->prepare($query);

        /**
         * Penggunaan htmlspecialchars dan strip_tags untuk mengamankan data input
         * dari penggunaan injeksi HTML atau javascript
         */
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));

        /**
         * Mengaitkan nilai ke parameter dalam query SQL
         */
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);

        /**
         * Mengeksekusi statement SQL yang sudah dibuat sebelumnya
         */
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($record_num,$records_per_page)
    {
        $query = "SELECT * FROM products LIMIT {$record_num},{$records_per_page}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}
