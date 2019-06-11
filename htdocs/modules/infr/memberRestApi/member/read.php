<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/member.php';

$database = new Database();
$db = $database->getConnection();

$member = new Member($db);

$stmt = $member->read();
$num = $stmt->rowCount();

if($num>0) {
    $memberArr = array();
    $memberArr['records'] = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $memberInfo = array(
            "seq" => $ifmbSeq,
            "id" => $ifmbId,
            "firstname" => $ifmbFirstName,
            "lastName" => $ifmbLastName,
            "Dob" => $ifmbDob,
            "genderCd" => $ifmbGenderCd,
            "email" => $ifmeEmail,
            "phone2" => $ifmpPhone2,
            "phone3" => $ifmpPhone3,
            "address" => html_entity_decode($ifmaAddress)
        );

        array_push($memberArr["records"], $memberInfo);
    }

    http_response_code(200);

    echo json_encode($memberArr);
} else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
