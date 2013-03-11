<?php

Namespace Model;

class DBConfigureDataDrupal70 extends Base {

    private $settingsFileLocation = 'src/sites/default'; // no trail slash
    private $settingsFileName = 'settings.php';
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
        "'database'"=>"      'database' => '****DB NAME****',",
        "'username'"=>"      'username' => '****DB USER****',",
        "'password'"=>"      'password' => '****DB PASS****',",
        "'host'"=>"      'host' => '****DB HOST****'," );

    }

}