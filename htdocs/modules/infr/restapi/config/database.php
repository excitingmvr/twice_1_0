<?php 

    class Database {
        private $host = 'trip.cnduywc7dehh.ap-northeast-2.rds.amazonaws.com:33062';
        private $userName = 'id190507';
        private $password = 'Qwer1234!!';
        private $dbName = 'api_db';
        

        public $connection;

        public function getConnection(){
 
            $this->conn = null;
     
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->userName, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
     
            return $this->conn;
            }
    }
?>