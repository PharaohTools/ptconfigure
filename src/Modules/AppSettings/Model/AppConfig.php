<?php

Namespace Model;

class AppConfig {

    private static function checkIsProject() {
        return file_exists('dhproj');
    }

    public static function setProjectVariable($variable, $value, $listAdd=null) {
        if (self::checkIsProject()) {
            $appConfigArray = self::loadProjectFile();
            if ( $listAdd == true ) { $appConfigArray[$variable][] = $value ; }
            else { $appConfigArray[$variable] = $value ; }
            self::saveProjectFile( $appConfigArray ) ; }
    }

    public static function getProjectVariable($variable) {
        $value = null;
        if (self::checkIsProject()) {
            $appConfigArray = self::loadProjectFile();
            $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : null ;
            self::saveProjectFile($appConfigArray); }
        return $value;
    }

    private static function loadProjectFile() {
        $appConfigArrayJSON = file_get_contents('dhproj');
        $decoded = json_decode($appConfigArrayJSON);
        if (is_object($decoded) || is_array($decoded)) { return new \ArrayObject($decoded); }
        else { return new \ArrayObject(array()); }
    }

    private static function saveProjectFile($appConfigArray) {
        $appConfigObject = new \ArrayObject($appConfigArray);
        $appConfigObjectJSON = json_encode($appConfigObject);
        file_put_contents('dhproj', $appConfigObjectJSON);
    }

    public static function setAppVariable($variable, $value, $listAdd=null) {
        $appConfigArray = self::loadAppFile();
        if ( $listAdd == true ) { $appConfigArray[$variable][] = $value ; }
        else { $appConfigArray[$variable] = $value ; }
        self::saveAppFile( $appConfigArray ) ;
    }

    public static function deleteAppVariable($variableToDelete) {
        $appConfigArray = self::loadAppFile();
        if (array_key_exists($variableToDelete, $appConfigArray) ) {
            unset($appConfigArray[$variableToDelete]) ; }
        self::saveAppFile( $appConfigArray ) ;
    }

    public static function getAppVariable($variable) {
        $appConfigArray = self::loadAppFile();
        $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : null ;
        return $value;
    }

    public static function getAllAppVariables() {
        $appConfigArray = self::loadAppFile();
        return $appConfigArray;
    }

    private static function loadAppFile() {
        $appFile = self::getAppBaseDir().'/dapperapp';
        if (!file_exists($appFile)){ shell_exec("touch ".$appFile); }
        $appConfigArrayJSON = file_get_contents($appFile);
        $decoded = json_decode($appConfigArrayJSON);
        $returnObject = (is_object($decoded)) ? new \ArrayObject($decoded) : new \ArrayObject(array()) ;
        return $returnObject;
    }

    private static function saveAppFile($appConfigArray) {
        $appConfigObject = new \ArrayObject($appConfigArray);
        $appConfigObjectJSON = json_encode($appConfigObject);
        file_put_contents(self::getAppBaseDir().'/dapperapp', $appConfigObjectJSON);
    }

    private static function getAppBaseDir() {
        $baseDir = dirname(__FILE__)."/../../..";
        return $baseDir;
    }

}