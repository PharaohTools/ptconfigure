<?php

Namespace Model;

class ApacheFastCGIModulesUbuntuModern extends ApacheFastCGIModulesUbuntu {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("14", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "addSources", "params" => array()) ),
            // @todo we should probably use the packagemanager for this
            array("command" => array( "apt-get update -y", ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libapache2-mod-fastcgi")) ),
            array("command" => array(
                "a2enmod proxy",
                "a2enmod proxy_fcgi",
//                "a2enconf php5-fpm",
            ) ),
            array("method"=> array("object" => $this, "method" => "apacheReload", "params" => array())) );
        $this->uninstallCommands = array(
            array("command" => array(
                "a2dismod proxy_fcgi",
//                "a2enconf php5-fpm",
            ) ),
            array("method"=> array("object" => $this, "method" => "apacheReload", "params" => array()))
        );
        $this->programDataFolder = "/opt/ApacheFastCGIModules"; // command and app dir name
        $this->programNameMachine = "apachefastcgimodules"; // command and app dir name
        $this->programNameFriendly = "Apache Fast CGI Mods!"; // 12 chars
        $this->programNameInstaller = "Apache Fast CGI Modules";
        $this->initialize();
    }


}
