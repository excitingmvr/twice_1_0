<?php
    include "inc_db.php";
    include "inc_env.php";

    function alert($msg) {
        echo "<script>window.alert('{$msg}');</script>";
    }
    function move($url) {
        echo "<script>document.location.href='$url'</script>";
    }
?>