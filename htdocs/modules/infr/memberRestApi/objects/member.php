<?php

    class Member {
        private $conn;
        private $tableName = "infrMember";
        
        // object properties
        public $ifmbSeq;
        public $ifmbId;
        public $ifmbFirstName;
        public $ifmbLastName;
        public $ifmbDob;
        public $ifmbGenderCd;
        public $ifmeEmail;
        public $ifmpPhone2;
        public $ifmpPhone3;
        public $ifmaAddress;

        public function __construct($db) {
            $this->conn = $db;
        }

        function read(){
 
            // select all query
            $query = "
                        SELECT 
                            ifmb.ifmbSeq
                            , ifmb.ifmbId
                            , ifmb.ifmbFirstName
                            , ifmb.ifmbLastName
                            , ifmb.ifmbDob
                            , ifmb.ifmbGenderCd
                            , ifme.ifmeEmail
                            , ifmp.ifmpPhone2
                            , ifmp.ifmpPhone3
                            , ifma.ifmaAddress 
                        FROM 
                            ".$this->tableName." AS ifmb 
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
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        // create member
        function create(){
            // sanitize
            $this->ifmbId=htmlspecialchars(strip_tags($this->ifmbId));
            $this->ifmbFirstName=htmlspecialchars(strip_tags($this->ifmbFirstName));
            $this->ifmbLastName=htmlspecialchars(strip_tags($this->ifmbLastName));
            $this->ifmbDob=htmlspecialchars(strip_tags($this->ifmbDob));
            $this->ifmbGenderCd=htmlspecialchars(strip_tags($this->ifmbGenderCd));
            $this->ifmaAddress=htmlspecialchars(strip_tags($this->ifmaAddress));
            $this->ifmbSeq=htmlspecialchars(strip_tags($this->ifmbSeq));
            $this->ifmeEmail=htmlspecialchars(strip_tags($this->ifmeEmail));
            $this->ifmpPhone2=htmlspecialchars(strip_tags($this->ifmpPhone2));
            $this->ifmpPhone3=htmlspecialchars(strip_tags($this->ifmpPhone3));
            // query to insert record
            $query = "
                        INSERT INTO
                            " . $this->tableName . "
                        SET
                            ifmbId=:ifmbId
                            , ifmbFirstName=:ifmbFirstName
                            , ifmbLastName=:ifmbLastName
                            , ifmbDob=:ifmbDob
                            , ifmbGenderCd=:ifmbGenderCd
                            , ifmbRegDatetime=now(6)
                            , ifmbDelNy=0
                    ";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
            // bind values
            $stmt->bindParam(":ifmbId", $this->ifmbId);
            $stmt->bindParam(":ifmbFirstName", $this->ifmbFirstName);
            $stmt->bindParam(":ifmbLastName", $this->ifmbLastName);
            $stmt->bindParam(":ifmbDob", $this->ifmbDob);
            $stmt->bindParam(":ifmbGenderCd", $this->ifmbGenderCd);
            
            // execute query
            if($stmt->execute()){
                $this->ifmbSeq = $this->conn->lastInsertId();
                // query to insert record
                $query = "
                            INSERT INTO
                                infrMemberAddress
                            SET
                                ifmaAddress=:ifmaAddress
                                , ifmaRegDatetime=now(6)
                                , ifmaDelNy=0
                                , ifmbSeq=:ifmbSeq;
                            INSERT INTO
                                infrMemberEmail
                            SET
                                ifmeEmail=:ifmeEmail
                                , ifmeRegDatetime=now(6)
                                , ifmeDelNy=0
                                , ifmbSeq=:ifmbSeq;
                            INSERT INTO
                                infrMemberPhone
                            SET
                                ifmpPhone2=:ifmpPhone2
                                , ifmpPhone3=:ifmpPhone3
                                , ifmpRegDatetime=now(6)
                                , ifmpDelNy=0
                                , ifmbSeq=:ifmbSeq;
                        ";

                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize

                //bind values
                $stmt->bindParam(":ifmaAddress", $this->ifmaAddress);
                $stmt->bindParam(":ifmeEmail", $this->ifmeEmail);
                $stmt->bindParam(":ifmpPhone2", $this->ifmpPhone2);
                $stmt->bindParam(":ifmpPhone3", $this->ifmpPhone3);
                $stmt->bindParam(":ifmbSeq", $this->ifmbSeq);

                if($stmt->execute()){
                    return true;
                }
                return false;
            }
            return false;
        }

        function readOne(){
 
            // query to read single record
            $query = "
                        SELECT 
                            ifmb.ifmbSeq
                            , ifmb.ifmbId
                            , ifmb.ifmbFirstName
                            , ifmb.ifmbLastName
                            , ifmb.ifmbDob
                            , ifmb.ifmbGenderCd
                            , ifme.ifmeEmail
                            , ifmp.ifmpPhone2
                            , ifmp.ifmpPhone3
                            , ifma.ifmaAddress 
                        FROM 
                            ".$this->tableName." AS ifmb 
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
                        AND ifmb.ifmbSeq = ?
                    ";
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // bind id of member to be updated
            $stmt->bindParam(1, $this->ifmbSeq);
         
            // execute query
            $stmt->execute();
         
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            // set values to object properties
            $this->ifmbSeq = $row['ifmbSeq'];
            $this->ifmbId = $row['ifmbId'];
            $this->ifmbFirstName = $row['ifmbFirstName'];
            $this->ifmbLastName = $row['ifmbLastName'];
            $this->ifmbDob = $row['ifmbDob'];
            $this->ifmbGenderCd = $row['ifmbGenderCd'];
            $this->ifmeEmail = $row['ifmeEmail'];
            $this->ifmpPhone2 = $row['ifmpPhone2'];
            $this->ifmpPhone3 = $row['ifmpPhone3'];
            $this->ifmaAddress = $row['ifmaAddress'];
        }

        function update(){
            $this->ifmbId=htmlspecialchars(strip_tags($this->ifmbId));
            $this->ifmbFirstName=htmlspecialchars(strip_tags($this->ifmbFirstName));
            $this->ifmbLastName=htmlspecialchars(strip_tags($this->ifmbLastName));
            $this->ifmbDob=htmlspecialchars(strip_tags($this->ifmbDob));
            $this->ifmbGenderCd=htmlspecialchars(strip_tags($this->ifmbGenderCd));
            $this->ifmaAddress=htmlspecialchars(strip_tags($this->ifmaAddress));
            $this->ifmbSeq=htmlspecialchars(strip_tags($this->ifmbSeq));
            $this->ifmeEmail=htmlspecialchars(strip_tags($this->ifmeEmail));
            $this->ifmpPhone2=htmlspecialchars(strip_tags($this->ifmpPhone2));
            $this->ifmpPhone3=htmlspecialchars(strip_tags($this->ifmpPhone3));
            // update query
            $query = "
                        UPDATE 
                            ".$this->tableName." 
                        SET 
                            ifmbId =:ifmbId
                            , ifmbFirstName =:ifmbFirstName
                            , ifmbLastName =:ifmbLastName
                            , ifmbGenderCd =:ifmbGenderCd
                            , ifmbDob =:ifmbDob
                            , ifmbModDatetime = now(6)
                        WHERE ifmbSeq =:ifmbSeq;
                        
                        UPDATE 
                            infrMemberAddress 
                        SET 
                            ifmaAddress =:ifmaAddress
                            , ifmaModDatetime = now(6)
                        WHERE 
                            ifmbSeq =:ifmbSeq;
                        
                        UPDATE 
                            infrMemberEmail
                        SET 
                            ifmeEmail =:ifmeEmail
                            , ifmeModDatetime = now(6)
                        WHERE 
                            ifmbSeq =:ifmbSeq;
                        
                        UPDATE 
                            infrMemberPhone 
                        SET 
                            ifmpPhone2 =:ifmpPhone2
                            , ifmpPhone3 =:ifmpPhone3
                            , ifmpModDatetime = now(6)
                        WHERE 
                            ifmbSeq =:ifmbSeq;
            ";
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            
            // bind values
            $stmt->bindParam(":ifmbId", $this->ifmbId);
            $stmt->bindParam(":ifmbFirstName", $this->ifmbFirstName);
            $stmt->bindParam(":ifmbLastName", $this->ifmbLastName);
            $stmt->bindParam(":ifmbDob", $this->ifmbDob);
            $stmt->bindParam(":ifmbGenderCd", $this->ifmbGenderCd);
            $stmt->bindParam(":ifmaAddress", $this->ifmaAddress);
            $stmt->bindParam(":ifmeEmail", $this->ifmeEmail);
            $stmt->bindParam(":ifmpPhone2", $this->ifmpPhone2);
            $stmt->bindParam(":ifmpPhone3", $this->ifmpPhone3);
            $stmt->bindParam(":ifmbSeq", $this->ifmbSeq);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        function delete(){
 
            // delete query
            $query = "
                        UPDATE infrMember AS ifmb
                            , infrMemberAddress AS ifma
                            , infrMemberEmail AS ifme
                            , infrMemberPhone AS ifmp 
                        SET ifmb.ifmbDelNy = 1
                            , ifma.ifmaDelNy = 1
                            , ifme.ifmeDelNy = 1
                            , ifmp.ifmpDelNy = 1  
                        WHERE 
                            ifmb.ifmbSeq=:seq
                        AND 
                            ifma.ifmbSeq=:seq
                        AND 
                            ifme.ifmbSeq=:seq
                        AND 
                            ifmp.ifmbSeq=:seq;
                    ";
         
            // prepare query
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $this->ifmbSeq=htmlspecialchars(strip_tags($this->ifmbSeq));
         
            // bind id of record to delete
            $stmt->bindParam(":seq", $this->ifmbSeq);
         
            // execute query
            if($stmt->execute()){
                $query = "
                    delete from infrMemberEmail where ifmbSeq = :seq;
                    delete from infrMemberAddress where ifmbSeq = :seq;
                    delete from infrMemberPhone where ifmbSeq = :seq;
                    delete from infrMember where ifmbSeq = :seq;
                ";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // bind id of record to delete
                $stmt->bindParam(":seq", $this->ifmbSeq);
                
                if($stmt->execute()){
                    return true;
                } else {
                    return false;
                }        
            return false;
            }
        }

        function search($keywords){
 
            // select all query
            $query = "
                        SELECT 
                            ifmb.ifmbSeq
                            , ifmb.ifmbId
                            , ifmb.ifmbFirstName
                            , ifmb.ifmbLastName
                            , ifmb.ifmbDob
                            , ifmb.ifmbGenderCd
                            , ifme.ifmeEmail
                            , ifmp.ifmpPhone2
                            , ifmp.ifmpPhone3
                            , ifma.ifmaAddress 
                        FROM 
                            ".$this->tableName." AS ifmb 
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
                        AND
                            ifmb.ifmbSeq LIKE :keyword
                        OR 
                            ifmb.ifmbId LIKE :keyword
                        OR
                            ifmb.ifmbFirstName LIKE :keyword
                        OR
                            ifmb.ifmbLastName LIKE :keyword
                        OR
                            ifme.ifmeEmail LIKE :keyword
                        OR
                            ifmp.ifmpPhone2 LIKE :keyword
                        OR
                            ifmp.ifmpPhone3 LIKE :keyword
                        OR
                            ifma.ifmaAddress LIKE :keyword;
            ";
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $keywords=htmlspecialchars(strip_tags($keywords));
            $keywords = "%{$keywords}%";
         
            // bind
            $stmt->bindParam(":keyword", $keywords);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        public function readPaging($from_record_num, $records_per_page){
 
            // select query
            $query = "
                        SELECT 
                            ifmb.ifmbSeq
                            , ifmb.ifmbId
                            , ifmb.ifmbFirstName
                            , ifmb.ifmbLastName
                            , ifmb.ifmbDob
                            , ifmb.ifmbGenderCd
                            , ifme.ifmeEmail
                            , ifmp.ifmpPhone2
                            , ifmp.ifmpPhone3
                            , ifma.ifmaAddress 
                        FROM 
                            ".$this->tableName." AS ifmb 
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
                        ORDER BY 
                            ifmb.ifmbSeq DESC
                        LIMIT 
                            ?, ?;
            ";
         
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
         
            // bind variable values
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
         
            // execute query
            $stmt->execute();
         
            // return values from database
            return $stmt;
        }

        public function count(){
            $query = "
                        SELECT 
                            COUNT(*) as total_rows 
                        FROM 
                            ".$this->tableName." AS ifmb
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
                            ifmb.ifmbDelNy = 0;
            ";
         
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            return $row['total_rows'];
        }
    }
?>