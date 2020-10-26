<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object file
include_once '../config/databaseDemo.php';
include_once '../objects/accountDemo.php';

// get database connection
$database = new DatabaseDemo();
$db = $database->getConnection();

// prepare product object
$account = new AccountDemo($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
// set ID property of product to be edited
$account->user_id = $data->user_id;
$account->name = $data->name;
$account->registerDate = $data->registerDate;
$account->expiredTime = $data->expiredTime;
$account->currentBalance = $data->currentBalance;
$account->joiningDate = $data->joiningDate;
$account->expiryDate = $data->expiryDate;

if($account->insertAccountDemo()) {
    // set response code - 200 OK
    http_response_code(200);
    // make it json format
    echo json_encode(true);
} else {
    // set response code - 404 Not found
    http_response_code(503);
    // tell the user product does not exist
    echo json_encode(false);
}
?>