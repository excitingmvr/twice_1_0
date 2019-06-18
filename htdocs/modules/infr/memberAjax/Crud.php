<?php 
    include_once 'DbConfig.php';
    
    class Crud extends DbConfig {
        
        private $selectQuery = "
            SELECT 
                ifmb.ifmbSeq
                , ifmb.ifmbId
                , ifmb.ifmbFirstName
                , ifmb.ifmbLastName
                , ifmb.ifmbDob
                , ifme.ifmeEmail
                , ifmp.ifmpPhone2
                , ifmp.ifmpPhone3
                , ifma.ifmaAddress 
            FROM 
                infrMember AS ifmb 
            INNER JOIN 
                infrMemberEmail AS ifme
            ON 
                ifmb.ifmbSeq = ifme.ifmbSeq 
            AND 
                ifme.ifmeDelNy = 0
            INNER JOIN 
                infrMemberPhone AS ifmp
            ON  
                ifmb.ifmbSeq = ifmp.ifmbSeq
            AND 
                ifmp.ifmpDelNy = 0
            INNER JOIN 
                infrMemberAddress AS ifma 
            ON 
                ifmb.ifmbSeq = ifma.ifmbSeq
            AND 
                ifma.ifmaDelNy = 0
            WHERE 
                ifmb.ifmbDelNy = 0
            AND
                1=1 
        ";

        public function __construct() {
            parent::__construct();
        }

        public function getConnection() {
            return $this->connection;
        }
        public function getInsertId() {
            return mysqli_insert_id($this->connection);
        }

        public function getData($query) {
            $result = mysqli_query($this->connection, $query);
            if($result == false) {
                return false;
            }
                $rows = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
                return $rows;
        }

        public function getOneData($query) {
            $result = mysqli_query($this->connection, $query);
            if ($result == false) {
                return false;
            }
            $row = mysqli_fetch_assoc($result);

            return $row;
        }

        public function excute($query) {
            $result = mysqli_query($this->connection, $query);

            if ($result == false) {
                echo 'Error: '.mysqli_error($this->connection);
                return false;
            } else {
                return $result;
            }
        }

        public function delete($seq, $table) {
            $query = "DELETE FROM $table WHERE ifmbSeq = $seq";
            
            $result = mysqli_query($this->connection, $query);
            
            if ($result == false) {
                echo 'Error: cannot delete id ' . $seq . ' from table ' . $table;
                return false;
            } else {
                return true;
            }
        }

        public function escape_string($value) {
            return mysqli_real_escape_string($this->connection, $value);
        }

        public function alert($msg) {
            echo "<script>window.alert('{$msg}');</script>";
        }

        public function move($url) {
            echo "<script>document.location.href='$url'</script>";
        }

        public function optionSelected($variable, $value){
            if ($variable == $value) {
                echo "selected";
            }
        }
        

        public function getSelectQuery() {
            return $this->selectQuery;
        }
    }
?>

