<?php

Namespace Model;

class AppConfig {


    private static function checkSettingsExistOrCreateIt() {
        if (file_exists(dirname(__FILE__).'../../../cleovars')) {
            return true; }
        else {
            touch(dirname(__FILE__).'../../../cleovars');
            return true; }
    }

    public static function setProjectVariable($variable, $value, $listAdd=null) {
        if (self::checkSettingsExistOrCreateIt()) {
            $appConfigArray = self::loadDHProjectFile();
            if ( $listAdd == true ) { $appConfigArray[$variable][] = $value ; }
            else { $appConfigArray[$variable] = $value ; }
            self::saveDHProjectFile( $appConfigArray ) ; }
    }

    public static function getProjectVariable($variable) {
        $value = null;
        if (self::checkSettingsExistOrCreateIt()) {
            $appConfigArray = self::loadDHProjectFile();
            $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : null ; }
        return $value;
    }

    private static function loadDHProjectFile() {
        $appConfigArraySerialized = file_get_contents(dirname(__FILE__).'../../../cleovars');
        $decoded = unserialize($appConfigArraySerialized);
        $appConfigArray = (is_object($decoded)) ? $decoded : array() ;
        return new \ArrayObject($appConfigArray);
    }

    private static function saveDHProjectFile($appConfigArray) {
        $appConfigObject = new \ArrayObject($appConfigArray);
        $appConfigObjectSerialized = serialize($appConfigObject);
        file_put_contents(dirname(__FILE__).'../../../cleovars', $appConfigObjectSerialized);
    }

}