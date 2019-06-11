<?php
// required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/member.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare member object
    $member = new Member($db);
    
    // set ID property of record to read
    $member->ifmbSeq = isset($_GET['seq']) ? $_GET['seq'] : die();
    
    // read the details of member to be edited
    $member->readOne();
    
    if($member->ifmbSeq!=null){
        // create array
        $memberInfo = array(
            "ifmbSeq" =>  $member->ifmbSeq,
            "ifmbId" => $member->ifmbId,
            "ifmbFirstName" => $member->ifmbFirstName,
            "ifmbLastName" => $member->ifmbLastName,
            "ifmbDob" => $member->ifmbDob,
            "ifmbGenderCd" => $member->ifmbGenderCd,
            "ifmeEmail" => $member->ifmeEmail,
            "ifmpPhone2" => $member->ifmpPhone2,
            "ifmpPhone3" => $member->ifmpPhone3,
            "ifmaAddress" => $member->ifmaAddress
        
        );
    
        // set response code - 200 OK
        http_response_code(200);
    
        // make it json format
        echo json_encode($memberInfo);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user member does not exist
        echo json_encode(array("message" => "member does not exist."));
    }
?>