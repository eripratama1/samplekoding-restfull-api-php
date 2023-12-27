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
     /**
     * Kode ini melakukan pengecekan method request
     * Jika method request yang dikirmkan bukan PUT atau PATCH
     * maka pesan error akan ditampilkan
     */
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed. Use method PUT or PATCH"));
        /**
         * Untuk seterusnya kode dibawah sama dengan yang ada pada file create.php
         * yang beda hanya method yang digunakan yaitu update ($functions->update())
         */
    } elseif (!empty($data->id) && !empty($data->name) && !empty($data->description) && !empty($data->price)) {
        if ($functions->update($data->id, $data->name, $data->description, $data->price)) {
            http_response_code(201);
            echo json_encode(array('message' => 'Product updated'));
        } else {
            http_response_code(503);
            echo json_encode(array('message' => 'Product failed to update'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'Unable to update' . 'Incomplete data'));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Internal server error :" . $e->getMessage()));
}
