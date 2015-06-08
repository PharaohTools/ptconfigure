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
        $path = getenv('SystemRoot').'\system32\drivers\etc\hosts' ;
        $command = 'move '.$this->baseTempDir.DS.'hostfile'.DS.'hosts '.$path;
        self::executeAndOutput($command);
        $command = 'del /S /Q '.$this->baseTempDir.DS.'hostfile';
        self::executeAndOutput($command);
    }


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