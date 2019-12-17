<?php

Namespace Model;

class DBConfigureDataGCFW2 extends Base {

    private $settingsFileLocation = 'src/Core'; // no trail slash
    private $settingsFileName = 'Database.php';
    private $settingsFileReplacements ;
    private $extraConfigFileReplacements ;
    private $extraConfigFiles = array('build/tests/phpunit/bootstrap.php'); // extra files requiring db config

    public function __construct(){
        $this->setReplacements();
        $this->setExtraConfigReplacements();
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return "" ; // @todo what even is this
    }

    private function setReplacements(){

        $this->settingsFileReplacements = array(
            '$this->dbName =' => '            $this->dbName = "****DB NAME****" ; // ****DBNAMEHOLDER****',
            '$this->dbUser =' => '            $this->dbUser = "****DB USER****" ; // ****DBUSERHOLDER****',
            '$this->dbPass =' => '            $this->dbPass = "****DB PASS****" ; // ****DBPASSHOLDER****',
            '$this->dbHost =' => '            $this->dbHost = "****DB HOST****" ; // ****DBHOSTHOLDER****');

    }

    private function setExtraConfigReplacements(){

        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$bootstrapDbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$bootstrapDbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$bootstrapDbHost = "****DB HOST****" ; ');

    }

}