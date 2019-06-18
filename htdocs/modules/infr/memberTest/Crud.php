<?php 
    include_once 'Base.php';
    
    class Crud extends Base {
        
        public function __construct() {
            parent::__construct();
        }

        public function getConnection() {
            return $this->connection;
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
            
        public function update($query) {
            $result = mysqli_query($this->connection, $query);

            if ($result == false) {
                echo 'Error: '.mysqli_error($this->connection);
                return false;
            } else {
                return $result;
            }
        }

        public function insert($query) {
            $result = mysqli_query($this->connection, $query);

            if ($result == false) {
                echo 'Error: '.mysqli_error($this->connection);
                return false;
            } else {
                $this->insertSeq;
                return $result;
            }
        }

        public function getOne($query) {
            $result = mysqli_query($this->connection, $query);
            if($result == false){
                return false;
            }
            $row = mysqli_fetch_assoc($result);

            return $row;
        }


        public function alert($msg) {
            echo "<script>window.alert('{$msg}');</script>";
        }

        public function move($url) {
            echo "<script>document.location.href='$url'</script>";
        }
    }
?>

