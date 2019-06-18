<!--php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once './core.php';
    include_once './database.php';
    include_once './member.php';
    
    // instantiate database and member object
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize object
    $member = new Member($db);
    
    // get keywords
    $keywords=isset($_GET["keyword"]) ? $_GET["keyword"] : "";
    
    // query members
    $stmt = $member->search($keywords);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // members array
        $memberArr=array();
        $memberArr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
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
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show members data
        echo json_encode($memberArr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no members found
        echo json_encode(
            array("message" => "No members found.")
        );
    }
?-->
<?php
header("Access-Control-Allow-Origin: *");
header("content-type:text/html;charset:utf-8");
 
// include database and object files
include_once './core.php';
include_once './utilities.php';
include_once './database.php';
include_once './member.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and member object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$member = new Member($db);
 
// query members
$keywords=isset($_GET["keyword"]) ? $_GET["keyword"] : "";
    
    // query members
    $stmt = $member->search($keywords);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // members array
        $memberArr=array();
        $memberArr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
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
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show members data
        //echo json_encode($memberArr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no members found
        echo json_encode(
            array("message" => "No members found.")
        );
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>  
    <table border=1 width="100%">
        <thead>
            <tr>
            <?php
                foreach($memberArr['records'][0] as $key=>$value){
                    echo "<th>{$key}</th>";
                }
            ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($memberArr['records'] as $key => $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<td>{$value}</td>";
                    }
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <?php 
        if($memberArr['paging']['first'] != ""){
            echo "<a href=\"{$memberArr['paging']['first']}\">first</a> ";
        }
        foreach($memberArr['paging']['pages'] as $key=>$value) {
            echo "<a href=\"{$value['url']}\">{$value['page']}</a> " ;
        };

        if($memberArr['paging']['last'] != ""){
            echo "<a href=\"{$memberArr['paging']['last']}\">last</a>";
        }

    ?>
    <br><a href="./memberForm.html">go create</a>
</body>
</html>