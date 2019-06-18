<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/html; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object file
    include_once './database.php';
    include_once './member.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare member object
    $member = new Member($db);
    
    
    // set member id to be deleted
    $member->ifmbSeq = isset($_GET['seq']) ? $_GET['seq'] : die();
     
    // delete the member
    if($member->delete()){
    
        // set response code - 200 ok
        http_response_code(200);
        
        echo "<script>
        alert('Member was deleted.');
        location.href='./ajax.html';
        </script>";
        // tell the user
        // echo json_encode(array("message" => "member was deleted."));
    }
    
    // if unable to delete the member
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete member."));
    }
?>