<?php

Namespace Core ;

class Database {

    private $dbo ;
    private $dataHelpers;

    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;

    public function __construct($manual=null) {
        if ($manual==null) {
            $this->setConnectionVars();
            $this->runDb(); }
    }

    public function runDb(){
        $this->startConnection();
        $this->dataHelpers = new DatabaseHelpers();
    }

    private function setConnectionVars($overrides=null) {
        if (isset($overrides) && is_array($overrides)) {
            $this->dbHost = $overrides["dbHost"];
            $this->dbUser = $overrides["dbUser"];
            $this->dbPass = $overrides["dbPass"];
            $this->dbName = $overrides["dbName"]; }
        else {
            $this->dbHost = "localhost";
            $this->dbUser = "root";
            $this->dbPass = "ebayebay";
            $this->dbName = "ebaycodepractice"; }
    }

    private function startConnection() {
        try {
            $this->dbo = new \mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);}
        catch (\Exception $e){
            echo "Application Exception";
            throw new \Exception ; }
    }

    public function getDbo() {
        return $this->dbo;
    }

}