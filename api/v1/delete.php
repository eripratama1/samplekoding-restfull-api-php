<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$functions = new Functions($db);

$data = json_decode(file_get_contents("php://input"));

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed. Use method DELETE"));
        return;
    }

    if (!empty($data->id)) {
        /**Panggil fungsi delete yang ada pada file functions.php */
        if ($functions->delete($data->id)) {
            http_response_code(201);
            echo json_encode(array("message" => "Product deleted"));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete product"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete product. Incomplete data"));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Internal server error". $e->getMessage()));
}
