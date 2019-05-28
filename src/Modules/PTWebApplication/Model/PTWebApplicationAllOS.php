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

    protected function getFPMPoolDir() {
        $thisSystem = new \Model\SystemDetectionAllOS();
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $fpmPD = "/etc/fpm.d/" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->distro, array("Debian") ) ) {
            if (PHP_MAJOR_VERSION > 6) {
                $fpmPD = "/etc/php/7.".PHP_MAJOR_VERSION."/fpm/pool.d/" ; }
            else {
                $fpmPD = "/etc/php5/fpm/pool.d/" ; } }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->distro, array("Redhat") ) ) {
            $fpmPD = "/etc/php-fpm.d/" ; }
        else {
            if (PHP_MAJOR_VERSION > 6) {
                $fpmPD = "/etc/php/7.".PHP_MAJOR_VERSION."/fpm/pool.d/" ; }
            else {
                $fpmPD = "/etc/php5/fpm/pool.d/" ; } }
        return $fpmPD ;
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