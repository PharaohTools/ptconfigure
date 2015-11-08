<?php

Namespace Model;

class PHPLdapAdminUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPLdapAdmin";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "phpldapadmin")) ),
//            array("method"=> array("object" => $this, "method" => "addInitScript", "params" => array())),
//            array("method"=> array("object" => $this, "method" => "haproxyRestart", "params" => array()))
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "phpldapadmin")) ),
//            array("method"=> array("object" => $this, "method" => "delInitScript", "params" => array())),
//            array("method"=> array("object" => $this, "method" => "haproxyRestart", "params" => array()))
        );
        $this->programDataFolder = "/opt/PHPLdapAdmin"; // command and app dir name
        $this->programNameMachine = "phpldapadmin"; // command and app dir name
        $this->programNameFriendly = "PHP LDAP Admin!"; // 12 chars
        $this->programNameInstaller = "PHP LDAP Admin";
        $this->statusCommand = SUDOPREFIX."phpldapadmin -v" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy phpldapadmin" ;
        $this->initialize();
    }

//    public function addInitScript() {
//        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
//        $templateSource = $templatesDir.'/haproxy';
//        $templatorFactory = new \Model\Templating();
//        $templator = $templatorFactory->getModel($this->params);
//        $newFileName = "/etc/default/haproxy" ;
//        $templator->template(
//            file_get_contents($templateSource),
//            array(),
//            $newFileName );
//        echo "HA Proxy Init script config file $newFileName added\n";
//    }
//
//    public function delInitScript() {
//        unlink("/etc/default/haproxy");
//        echo "HA Proxy Init script config file /etc/default/haproxy removed\n";
//    }

//    public function haproxyRestart() {
//        $serviceFactory = new Service();
//        $serviceManager = $serviceFactory->getModel($this->params) ;
//        $serviceManager->setService("haproxy");
//        $serviceManager->restart();
//    }

//    public function versionInstalledCommandTrimmer($text) {
//        $done = substr($text, 22, 8) ;
//        return $done ;
//    }
//
//    public function versionLatestCommandTrimmer($text) {
//        $done = substr($text, 44, 8) ;
//        return $done ;
//    }
//
//    public function versionRecommendedCommandTrimmer($text) {
//        $done = substr($text, 44, 8) ;
//        return $done ;
//    }

}