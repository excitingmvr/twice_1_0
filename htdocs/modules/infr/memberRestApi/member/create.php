<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/member.php';
 
$database = new Database();
$db = $database->getConnection();
 
$member = new Member($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->ifmbId) &&
    !empty($data->ifmbFirstName) &&
    !empty($data->ifmbLastName) &&
    !empty($data->ifmbDob) &&
    !empty($data->ifmbGenderCd) &&
    !empty($data->ifmeEmail) &&
    !empty($data->ifmpPhone2) &&
    !empty($data->ifmpPhone3) &&
    !empty($data->ifmaAddress) 
){
 
    // set product property values
    $member->ifmbId = $data->ifmbId;
    $member->ifmbFirstName = $data->ifmbFirstName;
    $member->ifmbLastName = $data->ifmbLastName;
    $member->ifmbDob = $data->ifmbDob;
    $member->ifmbGenderCd = $data->ifmbGenderCd;
    $member->ifmeEmail = $data->ifmeEmail;
    $member->ifmpPhone2 = $data->ifmpPhone2;
    $member->ifmpPhone3 = $data->ifmpPhone3;
    $member->ifmaAddress = $data->ifmaAddress;
    











    // create the product
    if($member->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Product was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>