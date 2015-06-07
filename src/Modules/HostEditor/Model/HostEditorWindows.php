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
        $command = 'mv '.$this->baseTempDir.DS.'hostfile'.DS.'hosts '.$path;
        self::executeAndOutput($command);
        $command = 'rm -rf '.$this->baseTempDir.DS.'hostfile';
        self::executeAndOutput($command);
    }

}