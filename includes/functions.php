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

    /**
     * Method read untuk menampilkan data dari tabel products
     * $record_num: Parameter ini menentukan nomor catatan atau baris dari mana query akan dimulai. 
     * Misalnya, jika kita ingin halaman pertama dari hasil query, kita akan mengatur $record_num menjadi 0
    
       $records_per_page: Parameter ini menentukan jumlah catatan atau baris yang akan diambil dalam satu halaman. 
       Misalnya, jika kita ingin menampilkan 10 produk per halaman, kita akan mengatur $records_per_page menjadi 10.

     */
    public function read($record_num, $records_per_page)
    {
        /**
         * LIMIT {$record_num},{$records_per_page}: Menetapkan batasan pada jumlah baris yang diambil. 
         * Dimulai dari baris ke-$record_num, diambil sebanyak $records_per_page baris.
         */
        $query = "SELECT * FROM products LIMIT {$record_num},{$records_per_page}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /** 
     * Method count digunakan untuk menghitung total baris (jumlah data) dalam tabel produk
     */
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    /**
     * Method yang digunakan untuk mengambil detail produk berdasarkan ID
     */
    public function detail($id)
    {
        /**
         * query SQL yang digunakan untuk mengambil semua kolom dari tabel products di mana kolom id sama dengan nilai 
         * yang diberikan ($id). Hanya satu baris (LIMIT 0,1) yang akan diambil.
         */
        $query = "SELECT * FROM products WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Method update disini sama dengan method create
     * yang membedakannya proses querynya dan parameter yang digunakan
     */
    public function update($id, $name, $description, $price)
    {
        $query = "UPDATE products set name=:name, description=:description, price=:price WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));

        /**
         * Mengaitkan nilai ke parameter dalam query SQL
         */
        $stmt->bindParam(":id", $id);
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
}
