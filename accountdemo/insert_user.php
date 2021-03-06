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
$account->name = $data->name;
$account->username = $data->username;
$account->email = $data->email;
$account->password = $data->password;
$account->block = $data->block;
$account->sendEmail = $data->sendEmail;
$account->registerDate = $data->registerDate;
$account->lastvisitDate = $data->lastvisitDate;
$account->activation = $data->activation;
$account->params = $data->params;

$result = $account->insertUser();

if($result) {
    // set response code - 200 OK
    http_response_code(200);
    // make it json format
    echo json_encode($result);
} else {
    // set response code - 404 Not found
    http_response_code(503);
    // tell the user product does not exist
    echo json_encode(false);
}
?>