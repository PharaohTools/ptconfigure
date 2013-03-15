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
        return null();
    }

    private function setReplacements(){

        $this->settingsFileReplacements = array(
            '****DBNAMEHOLDER****' => '$this->dbName = "****DB NAME****" ; // ****DBNAMEHOLDER****',
            '****DBUSERHOLDER****' => '$this->dbUser = "****DB USER****" ; // ****DBUSERHOLDER****',
            '****DBPASSHOLDER****' => '$this->dbPass = "****DB PASS****" ; // ****DBPASSHOLDER****',
            '****DBHOSTHOLDER****' => '$this->dbHost = "****DB HOST****" ; // ****DBHOSTHOLDER****');

    }

    private function setExtraConfigReplacements(){

        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$bootstrapDbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$bootstrapDbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$bootstrapDbHost = "****DB HOST****" ; ');

    }

}