<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/html; charset:UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once './database.php';
    include_once './member.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare member object
    $member = new Member($db);
    
    // get id of member to be edited
    $data = new stdClass();
    $data->ifmbSeq = $_POST['seq'];
    $data->ifmbId = $_POST['userId'];
    $data->ifmbFirstName = $_POST['firstName'];
    $data->ifmbLastName = $_POST['lastName'];
    $data->ifmbDob = "{$_POST['year']}-{$_POST['month']}-{$_POST['day']}";
    $data->ifmbGenderCd = $_POST['gender'];
    $data->ifmeEmail = $_POST['email'];
    $data->ifmpPhone2 = $_POST['middleNumber'];
    $data->ifmpPhone3 = $_POST['lastNumber'];
    $data->ifmaAddress = $_POST['address'];


    // set ID property of member to be edited
    
    $member->ifmbSeq = $data->ifmbSeq;
    $member->ifmbId = $data->ifmbId;
    $member->ifmbFirstName = $data->ifmbFirstName;
    $member->ifmbLastName = $data->ifmbLastName;
    $member->ifmbDob = $data->ifmbDob;
    $member->ifmbGenderCd = $data->ifmbGenderCd;
    $member->ifmeEmail = $data->ifmeEmail;
    $member->ifmpPhone2 = $data->ifmpPhone2;
    $member->ifmpPhone3 = $data->ifmpPhone3;
    $member->ifmaAddress = $data->ifmaAddress;
    
    // update the member
    if($member->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "member was updated."));
    }
    
    // if unable to update the member, tell the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update member."));
    }
?>