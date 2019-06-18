<?php 

    class Base {

        private $host = 'trip.cnduywc7dehh.ap-northeast-2.rds.amazonaws.com:33062';
        private $name = 'id190507';
        private $password = 'Qwer1234!!';
        private $dbname = 'trip';

        protected $connection;
        protected $insertSeq;

        public function __construct() {
            if (!isset($this->connection)) {
                $this->connection = mysqli_connect($this->host, $this->name, $this->password, $this->dbname);
                
                if(!$this->connection) {
                    echo 'Cannot connect to database server';
                    exit;
                }
            }
            return $this->connection;
        }

        public function close() {
           $close = mysqli_close($this->connection);
            if($close == false) {
                return false;
            }
            return true;
        }
    }

?>