<?php

Namespace Model;

class AppConfig {


    private static function checkIsDHProject() {
        return file_exists('dhproj');
    }

    public static function setProjectVariable($variable, $value, $listAdd=null) {
        if (self::checkIsDHProject()) {
            $appConfigArray = self::loadDHProjectFile();
            if ( $listAdd == true ) { $appConfigArray[$variable][] = $value ; }
            else { $appConfigArray[$variable] = $value ; }
            self::saveDHProjectFile( $appConfigArray ) ; }
    }

    public static function getProjectVariable($variable) {
        $value = null;
        if (self::checkIsDHProject()) {
            $appConfigArray = self::loadDHProjectFile();
            $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : null ; }
        return $value;
    }

    private static function loadDHProjectFile() {
        $appConfigArraySeialized = file_get_contents('dhproj');
        $decoded = unserialize($appConfigArraySeialized);
        $appConfigArray = (is_object($decoded)) ? $decoded : array() ;
        return new \ArrayObject($appConfigArray);
    }

    private static function saveDHProjectFile($appConfigArray) {
        $appConfigObject = new \ArrayObject($appConfigArray);
        $appConfigObjectSeialized = serialize($appConfigObject);
        file_put_contents('dhproj', $appConfigObjectSeialized);
    }

}