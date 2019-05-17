<?php 
    include "../../../inc/inc_general.php";


    if(!empty($_POST['insert_name'])){
        $name = $_POST['insert_name'];
        $query = "insert into test (name) value ('$name');";

        if($result = mysqli_query($link,$query)){
            move("./memberList.html");
        } else {
            die("update fail");
        }
    };


    if(!empty($_POST['update_name']) && !empty($_POST['before_name']) && !empty($_POST['update_seq'])){
        $seq = $_POST['update_seq'];
        $before_name = $_POST['before_name'];
        $update_name = $_POST['update_name'];
        $query = "update test set name = '$update_name' where seq = '$seq' and name = '$before_name';";
        if($result = mysqli_query($link,$query)){
            move("./memberList.html");
        } else {
            die("update fail");
        }
    }

    if(!empty($_POST['delete_seq']) && !empty($_POST['delete_name'])){
        $seq = $_POST['delete_seq'];
        $delete_name = $_POST['delete_name'];
        $query = "delete from test where seq = $seq and name = '$delete_name';";
        if($result = mysqli_query($link,$query)){
            echo "<script>location.href='./memberList.html'</script>";
        } else {
            die("delete fail");
        }
    }

?>