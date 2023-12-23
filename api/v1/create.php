<?php

/** 
 * Mengatur header CORS  (Cross-Origin Resource Sharing)
 * dan menetapkan bahwa tipe konten dari respons adalah 
 * JSON dengan pengkodean karakter UTF-8.
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

/**
 * Memanggil file yang diperlukan seperti konfigurasi koneksi ke database
 * dan juga memanggil file functions yang digunakan untuk proses CRUD dan fungsi lainnya
 */
include_once '../../config/database.php';
include_once '../../includes/functions.php';

/**
 * Inisialisasi class Database dan Functions
 */
$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

/**
 * Kode ini akan mengambil data yahg dikirim dalam permintaan HTTP POST
 * lalu mengkonversinya dari format JSON menjadi object PHP
 */
$data = json_decode(file_get_contents("php://input"));

try {

    /**
     * Kode ini melakukan pengecekan method request
     * Jika method request yang dikirmkan bukan POST
     * maka pesan error akan ditampilkan
     */
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed. Use method POST"));

        /**
         * Kode else if dibawah akan memeriksa jika data JSON yang ada nilainya tidak kosong
         * maka panggil method create dari object $functions dan jika berhasil kita akan mendapatkan 
         * response 201 dan pesan "Product created".
         * 
         * Jika tidak berhasil response yang akan diterima yaitu 503 dengan pesan errornya 
         * "Failed to create product". Jika data tidak lengkap response yang aka diterima
         * 400 dengan pesan Unable to create product
         */
    } elseif (!empty($data->name) && !empty($data->description) && !empty($data->price)) {
        if ($functions->create($data->name, $data->description, $data->price)) {
            http_response_code(201);
            echo json_encode((array("message" => "Product created")));
        } else {
            http_response_code(503);
            echo json_encode((array("message" => "Failed create product")));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create product"));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Internal server error :" . $e->getMessage()));
}
