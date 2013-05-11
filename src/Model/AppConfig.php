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
        return new \ArrayObject($decoded);
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

    private static function loadAppFile() {
        $appFile = self::getAppBaseDir().'/cleoapp';
        if (!file_exists($appFile)){ shell_exec("touch ".$appFile); }
        $appConfigArrayJSON = file_get_contents($appFile);
        $decoded = json_decode($appConfigArrayJSON);
        return new \ArrayObject($decoded);
    }

    private static function saveAppFile($appConfigArray) {
        $appConfigObject = new \ArrayObject($appConfigArray);
        $appConfigObjectJSON = json_encode($appConfigObject);
        file_put_contents(self::getAppBaseDir().'/cleoapp', $appConfigObjectJSON);
    }

    private static function getAppBaseDir() {
        $modelDir = dirname(__FILE__);
        $baseDir = substr($modelDir, 0, strlen($modelDir)-6);
        return $baseDir;
    }

}