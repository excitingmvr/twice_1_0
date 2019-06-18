<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset:UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

error_reporting(E_ALL);

ini_set("display_errors", 1);


// get database connection
include_once './database.php';
 
// instantiate product object
include_once './member.php';
 
$database = new Database();
$db = $database->getConnection();
 
$member = new Member($db);
 
// get posted data
$data = new stdClass();
$data->ifmbId = $_POST['userId'];
$data->ifmbPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
$data->ifmbFirstName = $_POST['firstName'];
$data->ifmbLastName = $_POST['lastName'];
$data->ifmbDob = "{$_POST['year']}-{$_POST['month']}-{$_POST['day']}";
$data->ifmbGenderCd = $_POST['gender'];
$data->ifmeEmail = $_POST['email'];
$data->ifmpPhone2 = $_POST['middleNumber'];
$data->ifmpPhone3 = $_POST['lastNumber'];
$data->ifmaAddress = $_POST['address'];

var_dump($data);

// make sure data is not empty
if(
    !empty($data->ifmbId) &&
    !empty($data->ifmbPassword) &&
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
    $member->ifmbPassword = $data->ifmbPassword;
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
        echo "<script>
            alert('Member was created.');
            location.href='./read_paging.php';
        </script>";
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create Member."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create member. Data is incomplete."));
}
?>