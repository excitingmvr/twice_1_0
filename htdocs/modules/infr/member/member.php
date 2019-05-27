<?php 
    include "../../../inc/inc_general.php";
    //print_r($_POST);

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

    if($_POST['process'] == "insert") {  
        $Dob = $_POST['year']."-".$_POST['month']."-".$_POST['day'];

        if ($_POST['password'] != $_POST['checkPassword']){

            echo "비밀번호가 일치하지 않습니다.";
        } else {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


            $query = "
                        INSERT INTO infrmember (
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
            $result = mysqli_query($link, $query);
            if ($result == true) {
                $foreignKey = mysqli_insert_id($link);

                $query2 = "INSERT INTO infrmemberaddress (
                    ifmaAddress
                    , ifmaRegDatetime
                    , ifmbSeq
                    , ifmaDelNy)
                    VALUES (
                        '$address'
                        , NOW(6)
                        , '$foreignKey'
                        , 0);";
                $result2 = mysqli_query($link, $query2);
                    
                $query3 = "INSERT INTO infrmemberemail (
                    ifmeEmail
                    , ifmeRegDatetime
                    , ifmbSeq
                    , ifmeDelNy)
                    VALUES (
                    '$email'
                    , NOW(6)
                    , '$foreignKey'
                    , 0);";

                $result3 = mysqli_query($link, $query3);

                $query4 = "INSERT INTO infrmemberphone (
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
                    , 0);";
                $result4 = mysqli_query($link, $query4);

                move("./memberlist.html");
            }
        }
    } else if ($_POST['process'] == "update"){ 
        $seq = $_POST['seq']; // 삭제

        $query = "UPDATE  infrmember 
        SET ifmbId = '$userId'
        , ifmbFirstName = '$firstName'
        , ifmbLastName = '$lastName'
        , ifmbGenderCd = '$gender'
        , ifmbDob = \"$Dob\"
        , ifmbModDatetime = NOW(6)
        WHERE ifmbSeq = $seq;";
        $result = mysqli_query($link, $query);

        if ($result == true) {
            $query2 = "UPDATE infrmemberaddress 
            SET ifmaAddress = '$address'
                , ifmaModDatetime = NOW(6)
                WHERE ifmbSeq = $seq;";
                
                
            $result2 = mysqli_query($link, $query2);
                
            $query3 = "UPDATE infrmemberemail
             SET ifmeEmail = '$email'
                , ifmeModDatetime = NOW(6);";
            $result3 = mysqli_query($link, $query3);
            $query4 = "UPDATE infrmemberphone 
            SET ifmpPhone2 = '$middleNumber'
                , ifmpPhone3 = '$lastNumber'
                , ifmpModDatetime = NOW(6)
                WHERE imfbSeq = $seq;";
            $result4 = mysqli_query($link, $query4);
            echo $query."<br>".$query2."<br>".$query3."<br>".$query4;
            alert("update success");
            move("./memberlist.html");
        }
    } else if ($_POST['process'] == "delete") {
        $seq = $_POST['delete_seq'];

        $delQuery = "UPDATE infrmember AS ifmb
        , infrmemberAddress AS ifma
        , infrmemberEmail AS ifme
        , infrmemberPhone AS ifmp 
        SET ifmb.ifmbDelNy = 1
        , ifma.ifmaDelNy = 1
        , ifme.ifmeDelNy = 1
        , ifmp.ifmpDelNy = 1  
        WHERE ifmb.ifmbSeq = $seq
        AND ifma.ifmbSeq = $seq
        AND ifme.ifmbSeq = $seq
        AND ifmp.ifmbSeq = $seq;";

        $delResult = mysqli_query($link,$delQuery);
        alert("delete success");
        move("./memberList.html");

    }else{
        exit;
        // 무조건 elst는 만들것
        // bypass
    }

    // echo $query2;
    // echo $query3;
    // echo $query4;

    //select ifmbSeq, ifmbPassword, ifmbFirstName, ifmbLastName, ifmbGenderCd, ifmbDob from infrmember;
    //select ifmbSeq, ifmaAddress from infrmemberAddress;
    //select ifmbSeq, ifmeEmail from infrmemberEmail;
    //select ifmbSeq, ifmpPhone2, ifmpPhone3  from infrmemberphone;




    $selectQuery="SELECT ifmb.ifmbSeq
    , ifmb.ifmbId
    , ifmb.ifmbFirstName
    , ifmb.ifmbLastName
    , ifmb.ifmbDob
    , ifme.ifmeEmail
    , ifmp.ifmpPhone2
    , ifmp.ifmpPhone3
    , ifma.ifmaAddress 
    FROM infrmember AS ifmb 
    INNER JOIN infrmemberemail AS ifme
    ON ifmb.ifmbSeq = ifme.ifmbSeq 
    AND ifme.ifmeDelNy = 0
    INNER JOIN infrmemberPhone AS ifmp
    ON  ifmb.ifmbSeq = ifmp.ifmbSeq
    AND ifmp.ifmpDelNy = 0
    INNER JOIN infrmemberAddress AS ifma 
    ON ifmb.ifmbSeq = ifma.ifmbSeq
    AND ifma.ifmaDelNy = 0
    WHERE ifmb.ifmbDelNy = 0;";
    //delete 처리를 tableDelNy 값이 default가 NULL이라서 삭제문에서 컬럼값을 1로 바꾸는걸로 했어요 실제 삭제 x 
//     두개 다 해야 되
//     deleㅅㄷ- > 이건 실제로 지우는 거 
//     deletebyupdate -> 니가 한건 이거




    // if(!empty($_POST['insert_name'])){
    //     $name = $_POST['insert_name'];
    //     $query = "insert into test (name) value ('$name');";

    //     if($result = mysqli_query($link,$query)){
    //         move("./memberList.html");
    //     } else {
    //         die("update fail");
    //     }
    // };


    // if(!empty($_POST['update_name']) && !empty($_POST['before_name']) && !empty($_POST['update_seq'])){
    //     $seq = $_POST['update_seq'];
    //     $before_name = $_POST['before_name'];
    //     $update_name = $_POST['update_name'];
    //     $query = "update test set name = '$update_name' where seq = '$seq' and name = '$before_name';";
    //     if($result = mysqli_query($link,$query)){
    //         move("./memberList.html");
    //     } else {
    //         die("update fail");
    //     }
    // }

    // if(!empty($_POST['delete_seq']) && !empty($_POST['delete_name'])){
    //     $seq = $_POST['delete_seq'];
    //     $delete_name = $_POST['delete_name'];
    //     $query = "delete from test where seq = $seq and name = '$delete_name';";
    //     if($result = mysqli_query($link,$query)){
    //         echo "<script>location.href='./memberList.html'</script>";
    //     } else {
    //         die("delete fail");
    //     }
    // }

?>