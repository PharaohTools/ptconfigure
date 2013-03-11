<?php

Namespace Model;

class DBConfigureDataGCFW2 extends Base {

    private $settingsFileLocation = 'src/Core'; // no trail slash
    private $settingsFileName = 'Database.php';
    private $settingsFileReplacements ;

    public function __construct(){
        $this->setReplacements();
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return null();
    }

    private function setReplacements(){

        $this->settingsFileReplacements = array(
            '$this->dbName ='=>'$this->dbName = "****DB NAME****";',
            '$this->dbUser ='=>'$this->dbUser = "****DB USER****";',
            '$this->dbPass ='=>'$this->dbPass = "****DB PASS****";',
            '$this->dbHost ='=>'$this->dbHost = "****DB HOST****";');

    }

}