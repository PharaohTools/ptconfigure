<?php

Namespace Model;

// @todo my use of $isLocal and $pfile vars in this file have a programming age of about nine.
// @todo actually, cant I just do this in json or something - this is very convoluted to save variables

class AppConfig {

    private static function checkSettingsExistOrCreateIt($pfile = null) {
        $pfile = (isset($pfile)) ? $pfile : 'papyrusfile' ;
        if (!file_exists($pfile)) { touch($pfile) ; }
        return true;
    }

    public static function setProjectVariable($variable, $value, $listAdd=null, $listAddKey=null, $isLocal=false) {
        $pFile = ($isLocal) ? 'papyrusfilelocal' : 'papyrusfile' ;
        if (self::checkSettingsExistOrCreateIt($pFile)) {
            $appConfigArray = self::loadProjectFile($pFile);
            if ( $listAdd == true && $listAddKey==null ) {
                if ( is_array($appConfigArray[$variable]) && !in_array($value, $appConfigArray[$variable])) {
                    $appConfigArray[$variable][] = $value ; } }
            else if ( $listAdd == true && $listAddKey!=null ) {
                $appConfigArray[$variable][$listAddKey] = $value ; }
            else { $appConfigArray[$variable] = $value ; }
            self::saveProjectFile( $appConfigArray, null, $isLocal ) ; }
    }

    /*
     *  to delete a value from an array with keys call deleteProjectVariable($variable, $key)
     *  to delete a value from an array without keys call deleteProjectVariable($variable, "any", $value)
     *  to delete a plain variable call deleteProjectVariable($variable)
     *
     */
    public static function deleteProjectVariable($variable, $key=null, $value=null, $isLocal=false) {
        $pFile = ($isLocal) ? 'papyrusfilelocal' : 'papyrusfile' ;
        if (self::checkSettingsExistOrCreateIt($pFile)) {
            $appConfigArray = self::loadProjectFile($pFile);
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
            self::saveProjectFile( $appConfigArray, null, $isLocal ) ; }
    }

    public static function getProjectVariable($variable, $isLocal=false) {
        $value = array();
        $pFile = ($isLocal == true) ? 'papyrusfilelocal' : 'papyrusfile' ;
        if (self::checkSettingsExistOrCreateIt($pFile)) {
            $appConfigArray = self::loadProjectFile($pFile);
            $value = (isset($appConfigArray[$variable])) ? $appConfigArray[$variable] : null ; }
        return $value;
    }

    public static function loadProjectFile($pfile = null, $isLocal = false) {
        if ($isLocal == true) { $pfile = 'papyrusfilelocal' ; }
        if (is_null($pfile)) {$pfile = 'papyrusfile' ; }
        if (file_exists($pfile)) {
            $appConfigArraySerialized = file_get_contents($pfile);
            $decoded = json_decode($appConfigArraySerialized, true);
            return $decoded ; }
        return array();
    }

    public static function saveProjectFile($appConfigArray, $pfile = null, $isLocal = false) {
        if ($isLocal == true) { $pfile = 'papyrusfilelocal' ; }
        if (is_null($pfile)) {$pfile = 'papyrusfile' ; }
        $appConfigSerialized = json_encode($appConfigArray, JSON_PRETTY_PRINT);
        file_put_contents($pfile, $appConfigSerialized);
        // chmod($pfile, 0777);
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
        $appFile = self::getVarFileLocation();
        if (!file_exists($appFile)){ touch($appFile); }
        $appConfigArrayString = file_get_contents($appFile);
        $decoded = json_decode($appConfigArrayString, true);
        return $decoded;
    }

    private static function saveAppFile($appConfigArray) {
        $coded = json_encode($appConfigArray);
        $appFile = self::getVarFileLocation();
        file_put_contents($appFile, $coded);
    }

    private static function getVarFileLocation() {
        $baseDir = self::getAppBaseDir().DS.'ptconfigurevars' ;
        return $baseDir;
    }

    private static function getAppBaseDir() {
        $baseDir = PFILESDIR."ptconfigure".DS."ptconfigure";
        return $baseDir;
    }

}