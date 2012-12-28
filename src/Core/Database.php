<?php

Namespace Core ;

class Database {

    private $dbo ;
    private $dataHelpers;

    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPass = "ebayebay";
    private $dbName = "ebaycodepractice";

    public function __construct() {
        try { $this->startConnection();
              if ($this->dbo->connect_errno ) {
                throw new \Exception ("Unable to connect to Database: ".$this->dbo->connect_errno); } }
        catch (\Exception $e){
            echo "Application Exception: ".$e; }
        $this->dataHelpers = new DatabaseHelpers();
    }

    private function startConnection() {
        $this->dbo = new \mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
    }

    public function getDbo() {
        return $this->dbo;
    }

}