<?php

Namespace Model;

class DBConfigureDataDrupal70 extends Base {

    private $settingsFileLocation = 'src/sites/default/settings.php';

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call(){
        return null();
    }

}