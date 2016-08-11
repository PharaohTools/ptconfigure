<?php

Namespace Model;

class PTWebApplicationAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PTWebApplication";
        $this->programNameMachine = "ptwebapplication"; // command and app dir name
        $this->programNameFriendly = " PTWebApplication! "; // 12 chars
        $this->programNameInstaller = "PTWebApplication Helpers";
        $this->programExecutorTargetPath = 'ptwebapplication/src/Bootstrap.php';
    }

    protected function getApachePoolDir() {
        $thisSystem = new \Model\SystemDetectionAllOS();
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $apachePD = "/etc/fpm.d/" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Debian") ) ) {
            $apachePD = "/etc/php5/fpm/pool.d/" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Redhat") ) ) {
            $apachePD = "/etc/php5/fpm/pool.d/" ; }
        else {
            $apachePD = "/etc/php5/fpm/pool.d/" ; }
        return $apachePD ;
    }

    public function isNotOSX() {
        $thisSystem = new \Model\SystemDetectionAllOS();
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $isNotOSX = false ; }
        else {
            $isNotOSX = true ; }
        return $isNotOSX  ;
    }

}