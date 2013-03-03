<?php

Namespace Model;

class DBConfigureDataGCFW2 extends Base {

    private $settingsFileLocation = 'src/Core/Database.php';

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return null();
    }

}