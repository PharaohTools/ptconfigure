<?php

Namespace Model;

class SystemDetectionGenerateAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function generate() {

        $sys = new \Model\SystemDetectionAllOS() ;
        $target_file = PFILESDIR.PHARAOH_APP.DS.PHARAOH_APP.DS."system_detection" ;

        $ray = array (
            "os" => $sys->os,
            "distro" => $sys->os,
            "linuxType" => $sys->linuxType,
            "version" => $sys->version,
            "architecture" => $sys->architecture,
            "hostName" => $sys->hostName,
        );
        $json = json_encode($ray) ;
        $fpc = file_put_contents($target_file, $json) ;
        return ($fpc === false) ? false : true ;
    }


}
