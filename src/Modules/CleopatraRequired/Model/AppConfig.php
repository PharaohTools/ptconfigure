<?php

Namespace Model;

class AppConfig {


    private static function checkSettingsExistOrCreateIt() {
        if (!file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../cleovars')) {
            touch(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../cleovars'); }
        return true;
    }

    public static function setProjectVariable($variable, $value, $listAdd=null, $listAddKey=null) {
        if (self::checkSettingsExistOrCreateIt()) {
            $appConfigArray = self::loadDHProjectFile();
            if ( $listAdd == true && $listAddKey==null ) {
                if (!in_array($value, $appConfigArray[$variable])) {
                    $appConfigArray[$variable][] = $value ; } }
            else if ( $listAdd == true && $listAddKey!=null ) {
                $appConfigArray[$variable][$listAddKey] = $value ; }
            else { $appConfigArray[$variable] = $value ; }
            self::saveDHProjectFile( $appConfigArray ) ; }
    }

    /*
     *  to delete a value from an array with keys call deleteProjectVariable($variable, $key)
     *  to delete a value from an array without keys call deleteProjectVariable($variable, "any", $value)
     *  to delete a plain variable call deleteProjectVariable($variable)
     *
     */
    public static function deleteProjectVariable($variable, $key=null, $value=null) {
        if (self::checkSettingsExistOrCreateIt()) {
            $appConfigArray = self::loadDHProjectFile();
            if ( isset($key) ) {
                // if variable is array without keys, delete entry by value
                if ($key=="any" && isset($value)) {
                    for ($i = 0; $i<count($appConfigArray[$variable]); $i++) {
                        if ($appConfigArray[$variable][$i] == $value) {
                            unset($appConfigArray[$variable][$i]) ; } } }
                // if variable is array with keys, delete entry by key
                else if (isset($appConfigArray[$variable][$key]) && !isset($value)) {
                    unset($appConfigArray[$variable][$key]) ; } }
            else {
                unset($appConfigArray[$variable]) ; }
            self::saveDHProjectFile( $appConfigArray ) ; }
    }

    public static function getProjectVariable($variable) {
        $value = null;
        if (self::checkSettingsExistOrCreateIt()) {
            $appConfigArray = self::loadDHProjectFile();
            $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : array() ; }
        return $value;
    }

    private static function loadDHProjectFile() {
        $appConfigArraySerialized = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../cleovars');
        $decoded = unserialize($appConfigArraySerialized);
        return $decoded ;
    }

    private static function saveDHProjectFile($appConfigArray) {
        $appConfigObjectSerialized = serialize($appConfigArray);
        file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../cleovars', $appConfigObjectSerialized);
        chmod(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../cleovars', 0777);
    }

}