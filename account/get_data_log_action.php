<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/account.php';
  
// instantiate database and account object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$account = new Account($db);
// query account
$stmt = $account->getDataLogAction();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    $group_arr["records"]=array();
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
        $trade_item =array(
            "id" => $row['id'],
            "session_id" => $row['session_id'],
            "user_id" => $row['user_id'],
            "ip" => $row['ip'],
            "city" => $row['city'],
            "country" => $row['country'],
            "action" => $row['action'],
            "counter" => $row['counter'],
            "time" => $row['timestart'],
            "browser" => $row['browser']
        );
        array_push($group_arr["records"], $trade_item);
    }
    // set response code - 200 OK
    http_response_code(200);
  
    // show account data in json format
    echo json_encode($group_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no account found
    echo json_encode(
        array("message" => "No action found.")
    );
}