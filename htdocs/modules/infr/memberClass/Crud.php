<?php 
    include_once 'DbConfig.php';
    
    class Crud extends DbConfig {
        
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
    }
?>

