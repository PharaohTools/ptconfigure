<?php

Namespace Core ;

class Database {

    private $dbo ;
    private $dataHelpers;

    public function __construct() {
        try {
            $this->dbo = new \mysqli("localhost", "root", "ebayebay", "ebaycodepractice");
            if (mysqli_connect_errno()) {
                throw new \Exception ("Unable to connect to Database: ".mysqli_connect_error());
            }
        } catch (\Exception $e){
            echo "Application Exception: ".$e;
        }
        $this->dataHelpers = new DatabaseHelpers();
    }

    public function doQueryGetValue($query, Array $params = array() ) {
        if ($stmt = $this->dbo->prepare($query)) {
            foreach ($params as $param) { $stmt->bind_param("s", $param); }
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            return $value;
        }
    }

    public function getDbo() {
        return $this->dbo;
    }

    public function doQuery($query) {
        $queryExecutionResult = ($this->dbo->query($query) ) ? true : false ;
        return $queryExecutionResult;
    }

    public function doQueryAndGetValue2($query) {
        $resultSet = $this->dbo->query($query) ;
        $row = $resultSet->fetch_assoc();
        return $row["count(*)"];
    }

}