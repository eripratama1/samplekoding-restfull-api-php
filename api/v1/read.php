<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 2;
$record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $functions->read($record_num, $records_per_page);
$num = $stmt->rowCount();

if ($num > 0) {
    $products_arr = array();
    $products_arr["records"] = array();
    $products_arr["paging"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            "name" => $name,
            "description" => $description,
            "price" => $price
        );
        array_push($products_arr["records"], $product_item);
    }

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
