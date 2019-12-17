<?php

Namespace Model;

class HostEditorWindows extends HostEditorAllLinuxMac {

    // Compatibility
    public $os = array("WINNT", "Windows") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected function loadCurrentHostFile() {
        $path = getenv('SystemRoot').'\system32\drivers\etc\hosts' ;
        $this->hostFileData = file_get_contents($path);
        return (strlen($this->hostFileData)>0) ? true : false ;
    }

    protected function moveHostFileAsRoot(){
        $path = getenv('SystemRoot').'\System32\drivers\etc\hosts' ;
        $copy_from_temp = 'copy '.self::$tempDir.DS.'hostfile'.DS.'hosts '.$path;
        $res1 = self::executeAndGetReturnCode($copy_from_temp, true, true) ;
        if ($res1['rc'] != 0) { return false ; }
        $delete_temp = 'del /S /Q '.self::$tempDir.DS.'hostfile';
        $res2 = self::executeAndGetReturnCode($delete_temp, true, true) ;
        if ($res2['rc'] != 0) { return false ; }
        $hosts_perms = 'icacls "'.$path.'" /grant Everyone:M' ;
        $res3 = self::executeAndGetReturnCode($hosts_perms, true, true) ;
        if ($res3['rc'] != 0) { return false ; }
        return true ;
    }
/*
 * takeown /f "%windir%\system32\drivers\etc\hosts"
 * icacls "%windir%\system32\drivers\etc\hosts" /grant administrators:F
attrib -r -h -s hosts
 */

    protected function hostFileDataAdd($ipEntry, $uri){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hostFileLines = explode(PHP_EOL , $this->hostFileData) ;
        $newHostFileData = "";
        $logging->log("Attempting to add Host File Entry...", $this->getModuleName()) ;
        foreach ($hostFileLines as $line) {
            $ipOccurs = substr_count($line, $ipEntry) ;
            $uriOccurs = substr_count($line, $uri) ;
            $bothOccur = ( $ipOccurs==1 && $uriOccurs==1);
            if ( $bothOccur )  {
                $logging->log("Host file entry already exists for Host Name {$uri}, with IP {$ipEntry} no need to edit...", $this->getModuleName()) ;
                return true; }
            else if ( $uriOccurs )  {
                $logging->log("Host file entry already exists for Host Name {$uri}, with IP {$ipEntry} removing...", $this->getModuleName()) ;
                continue ; }
            else {
                $newHostFileData .= $line."\r\n" ; } }
        $logging->log("Adding requested entry {$uri}, with IP {$ipEntry} to host file data", $this->getModuleName()) ;
        $this->hostFileData .= "$ipEntry          $uri"."\r\n";
        $this->writeHostFileEntryToProjectFile();
    }

    protected function hostFileDataRemove($ipEntry, $uri){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hostFileLines = explode(PHP_EOL , $this->hostFileData) ;
        $newHostFileData = "";
        foreach ($hostFileLines as $line) {
            $ipOccurs = substr_count($line, $ipEntry) ;
            $uriOccurs = substr_count($line, $uri) ;
            $bothOccur = ( $ipOccurs==1 && $uriOccurs==1);
            if (isset($this->params["guess"])) {
                if ($uriOccurs) {
                    $logging->log("Host file entry exists, attempting to remove...", $this->getModuleName()) ;
                    continue ; } }
            if ( !$bothOccur )  { $newHostFileData .= $line."\r\n" ; } }
        $this->hostFileData = $newHostFileData;
        $this->deleteHostFileEntryFromProjectFile();
    }

}