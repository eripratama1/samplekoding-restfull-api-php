<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

/**
 * Kode berikut digunakan untuk mendapatkan nilai dari parameter 'id' yang ada dalam query string. kode ini 
 * menggunakan operator ternary untuk mengecek apakah 'id' ada dalam query string. 
 * Jika ada, nilai 'id' diambil. Jika tidak, maka skrip die() dijalankan, yang menghentikan akan eksekusi script 
 */
$id = isset($_GET['id']) ? $_GET['id'] : die();

/** 
 * Memanggil fungsi detail dari file Functions.php dan 
 * passing parameter yahg diperlukan
 */
$stmt = $functions->detail($id);
$num = $stmt->rowCount();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed. Use method GET"));
        return;
    }

    if ($num > 0) {
        /**
         * Mengambil satu baris hasil query dan menyimpannya dalam bentuk array asosiatif. 
         * Dalam konteks ini, kita berasumsi bahwa query tersebut hanya mengembalikan satu baris
         */
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        /** Mengekstrak nilai dari baris hasil ke dalam variabel terpisah, 
         * seperti $name, $description, dan $price. 
         * Ini membuat nilai-nilai ini dapat digunakan secara langsung dalam pembuatan array produk. */
        extract($row);

        /**
         * Membuat array $product_item yang berisi detail produk yang diambil dari hasil query.
         */
        $product_item = array(
            "name" => $name,
            "description" => $description,
            "price" => $price,
        );
        http_response_code(200);
        echo json_encode($product_item);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product not found"));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Internal server error :" . $e->getMessage()));
}
