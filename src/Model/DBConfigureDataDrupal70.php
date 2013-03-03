<?php

Namespace Model;

class DBConfigureDataDrupal70 extends Base {

    private $settingsFileLocation = 'src/sites/default'; // no trail slash
    private $settingsFileName = 'settings.php';

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return null();
    }

}