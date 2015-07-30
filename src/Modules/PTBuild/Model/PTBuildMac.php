<?php

Namespace Model;

class PTBuildMac extends PTBuildLinux {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function getUserShellAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Scripts'.DS.'create-mac-user.sh' ;
        $this->executeAsShell("sh $path");
        return $path ;
    }


}