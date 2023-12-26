<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

/** Mendapatkan nilai $_GET['page'] atau menggunakan nilai default 1 jika tidak ada */
$page = isset($_GET['page']) ? $_GET['page'] : 1;

/** Menentukan jumlah baris yang akan ditampilkan per halaman */
$records_per_page = 2;

/** Menghitung jumlah baris awal yang akan diambil dari database */
$record_num = ($records_per_page * $page) - $records_per_page;

/** 
 * Memanggil fungsi read dari file Functions.php dan 
 * passing parameter yahg diperlukan
 */
$stmt = $functions->read($record_num, $records_per_page);

/**
 * Hitung jumlah baris yang dikembalikan oleh query
 * Metode rowCount() digunakan pada objek statement ($stmt) untuk mengembalikan jumlah baris yang terpengaruh oleh 
 * operasi query SELECT.
 */
$num = $stmt->rowCount();

/**
 *  Melakukan pengecekan apakah ada lebih dari 0 baris yang ditemukan oleh query sebelumnya. 
 *  Jika ya, maka memproses hasilnya. Jika tidak, mengirimkan respons 404 (Not Found)
 */
if ($num > 0) {

    /** Inisialisasi array untuk menyimpan hasil query */
    $products_arr = array(); 

    /** Array untuk menyimpan data produk */
    $products_arr["records"] = array();

    /** Array untuk informasi halaman */
    $products_arr["paging"] = array();

    /**
     * Melakukan iterasi melalui hasil query dan menyusun data produk ke dalam array. 
     * Setiap produk direpresentasikan sebagai asosiasi array yang kemudian dimasukkan ke dalam array 
     * $products_arr["records"].
     */
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        /** Menyusun data produk ke dalam array */
        $product_item = array(
            "name" => $name,
            "description" => $description,
            "price" => $price
        );
        array_push($products_arr["records"], $product_item);
    }

    /**
     * Mendapatkan informasi halaman untuk paginasi 
     * seperti total baris, baris per halaman, total halaman, dan halaman saat ini. 
     * Informasi ini ditambahkan ke dalam array $products_arr["paging"]
    */
    $total_rows = $functions->count();
    $page_info = array(
        "total_rows" => $total_rows,
        "records_per_page" => $records_per_page,
        "total_pages" => ceil($total_rows / $records_per_page),
        "current_page" => $page
    );
    $products_arr["paging"] = $page_info;
    http_response_code(200);
    echo json_encode($products_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No products found"));
}
