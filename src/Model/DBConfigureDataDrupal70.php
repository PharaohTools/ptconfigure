<?php

Namespace Model;

class DBConfigureDataDrupal70 extends Base {

    private $settingsFileLocation = 'src/sites/default'; // no trail slash
    private $settingsFileName = 'settings.php';
    private $settingsFileReplacements ;
    private $extraConfigFileReplacements ;
    private $extraConfigFiles = array('build/config/phpunit/bootstrap.php'); // extra files requiring db config

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
        "'database'"=>"      'database' => '****DB NAME****',",
        "'username'"=>"      'username' => '****DB USER****',",
        "'password'"=>"      'password' => '****DB PASS****',",
        "'host'"=>"      'host' => '****DB HOST****'," );

    }

    private function setExtraConfigReplacements(){

        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$this->dbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$this->dbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$this->dbHost = "****DB HOST****" ; ');

    }

}