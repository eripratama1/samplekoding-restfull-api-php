<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

/**
 * Mengambil nilai parameter 'keyword' dari query string jika ada, 
 * jika tidak, jadikan string kosong
 */
$keywords = isset($_GET['keyword']) ? $_GET['keyword'] : '';

/**
 * Memanggil fungsi 'search' dari objek '$functions' dengan parameter '$keywords'
 */
$stmt = $functions->search($keywords);

/**
 * Menghitung jumlah baris hasil pencarian
 */
$num = $stmt->rowCount();


try {
    /**
     * Memeriksa REQUEST_METHOD yang dikirimkan, jika bukan metode GET, 
     * kirim respons 405 (Method Not Allowed)
     */
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed. Use method GET"));
        return;
    }

    /**
     * Jika hasil pencarian ditemukan jalankan kode dibawah
     */
    if ($num > 0) {
        $products_arr = array();
        $products_arr["records"] = array();

        /**
         * Melakukan iterasi melalui hasil pencarian
         */
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            /** Ekstrak nilai kolom dari baris hasil pencarian */
            extract($row);

            /** 
             * Membuat array untuk setiap produk yang berhasil didaptkan
             * dari tabel products
             */
            $product_item = array(
                "name" => $name,
                "description" => $description,
                "price" => $price
            );

            /**
             * Menambahkan produk ke dalam array '$products_arr'
             */
            array_push($products_arr["records"], $product_item);
        }

        http_response_code(201);
        echo json_encode($products_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product not found"));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Internal server error" . $e->getMessage()));
}
