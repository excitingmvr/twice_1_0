<?php 
    //include "../../../inc/inc_general.php";
    include_once "./Crud.php";
   // mysqli_close($link);

    $crud = new Crud();
    //print_r($_POST);

    $query_arr = $_GET;
    $query_arr['process'] = "list";

    $q = http_build_query($query_arr);

    print_r($query_arr);

    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $middleNumber = $_POST['middleNumber'];
    $lastNumber = $_POST['lastNumber'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $hash;


    $Dob = $year."-".$month."-".$day;
    if($_POST['process'] == "insert") {  
        

        if ($_POST['password'] != $_POST['checkPassword']){

            echo "비밀번호가 일치하지 않습니다.";
            $crud->move("./memberForm.html");
        } else {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


            $query = "
                        INSERT INTO infrMember (
                            ifmbId
                            , ifmbPassword
                            , ifmbFirstName
                            , ifmbLastName
                            , ifmbGenderCd
                            , ifmbDob
                            , ifmbRegDatetime
                            , ifmbDelNy) 
                        VALUES (
                            '$userId'
                            , '$hash'
                            , '$firstName'
                            , '$lastName'
                            , '$gender'
                            , \"$Dob\"
                            , now(6)
                            , 0);
                    "; 
            $result = $crud->excute($query);
            if ($result == true) {
                $foreignKey = $crud->getInsertId();

                $query2 = "
                            INSERT INTO infrMemberAddress (
                                ifmaAddress
                                , ifmaRegDatetime
                                , ifmbSeq
                                , ifmaDelNy)
                            VALUES (
                                '$address'
                                , NOW(6)
                                , '$foreignKey'
                                , 0);
                        ";
                $result2 = $crud->excute($query2);
                    
                $query3 = "
                            INSERT INTO infrMemberEmail (
                                ifmeEmail
                                , ifmeRegDatetime
                                , ifmbSeq
                                , ifmeDelNy)
                            VALUES (
                                '$email'
                                , NOW(6)
                                , '$foreignKey'
                                , 0);
                            ";

                $result3 = $crud->excute($query3);

                $query4 = "
                            INSERT INTO infrMemberPhone (
                                ifmpPhone2
                                , ifmpPhone3
                                , ifmpRegDatetime
                                , ifmbSeq
                                , ifmpDelNy)
                            VALUES (
                                '$middleNumber'
                                , '$lastNumber'
                                , NOW(6)
                                , '$foreignKey'
                                , 0);
                            ";
                $result4 = $crud->excute($query4);
                $crud->alert("insert success");
                echo("<script>location.replace('/modules/infr/memberAjax/memberList.html?$q');</script>");
            }
        }
    } else if ($_POST['process'] == "update"){ 
        $seq = $_POST['seq'];

        $query = "
                    UPDATE 
                        infrMember 
                    SET 
                        ifmbId = '$userId'
                        , ifmbFirstName = '$firstName'
                        , ifmbLastName = '$lastName'
                        , ifmbGenderCd = '$gender'
                        , ifmbDob = \"$Dob\"
                        , ifmbModDatetime = NOW(6)
                    WHERE ifmbSeq = $seq;
                ";
        $result = $crud->excute($query);

        if ($result == true) {
            $query2 = "
                        UPDATE 
                            infrMemberAddress 
                        SET 
                            ifmaAddress = '$address'
                            , ifmaModDatetime = NOW(6)
                        WHERE 
                            ifmbSeq = $seq;
                    ";
                
                
            $result2 = $crud->excute($query2);
                
            $query3 = "
                        UPDATE 
                            infrMemberEmail
                        SET 
                            ifmeEmail = '$email'
                            , ifmeModDatetime = NOW(6)
                        WHERE 
                            ifmbSeq = $seq;
                    ";


            $result3 = $crud->excute($query3);


            $query4 = "
                        UPDATE 
                            infrMemberPhone 
                        SET 
                            ifmpPhone2 = '$middleNumber'
                            , ifmpPhone3 = '$lastNumber'
                            , ifmpModDatetime = NOW(6)
                        WHERE 
                            imfbSeq = $seq;
                    ";

            $result4 = $crud->excute($query4);

            echo $query."<br>".$query2."<br>".$query3."<br>".$query4;
            $crud->alert("update success");
            echo("<script>location.replace('/modules/infr/memberAjax/memberList.html?$q');</script>");
        }
    } else if ($_POST['process'] == "delete") {
        $seq = $_POST['delete_seq'];

        $delQuery = "
                        UPDATE infrMember AS ifmb
                            , infrMemberAddress AS ifma
                            , infrMemberEmail AS ifme
                            , infrMemberPhone AS ifmp 
                        SET ifmb.ifmbDelNy = 1
                            , ifma.ifmaDelNy = 1
                            , ifme.ifmeDelNy = 1
                            , ifmp.ifmpDelNy = 1  
                        WHERE 
                            ifmb.ifmbSeq = $seq
                        AND 
                            ifma.ifmbSeq = $seq
                        AND 
                            ifme.ifmbSeq = $seq
                        AND 
                            ifmp.ifmbSeq = $seq;
                    ";

        $delResult = $crud->excute($delQuery);

        $crud->alert("delete success");
        echo("<script>location.replace('/modules/infr/memberAjax/memberList.html?$q');</script>");

    }else{
        exit;
        // 무조건 else는 만들것
        // bypass
    }
    
?>

