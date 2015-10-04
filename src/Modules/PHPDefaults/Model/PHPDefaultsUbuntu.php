<?php

Namespace Model;

class PHPDefaultsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $packages = array("php5-PHPDefaults") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", $this->packages ) ) ),
//            array("method"=> array("object" => $this, "method" => "templatePHPDefaultsConfig", "params" => array()) ),
        );
        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPDefaults"; // command and app dir name
        $this->programNameMachine = "PHPDefaults"; // command and app dir name
        $this->programNameFriendly = "PHP Defaults!"; // 12 chars
        $this->programNameInstaller = "PHP Default Settings";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'php -m';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $pax = $this->packages ;
        foreach ($pax as &$pack) { $pack = substr($pack, 5) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($pax as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("PHP Module {$modToCheck} is not installed for this PHP installation.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

    public function templatePHPDefaultsConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating PHP Defaults Configuration, ensuring our session save path of /tmp is set.", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/etc/php-PHPDefaults.conf" ;
        $params["search"] = "php_admin_value[session.save_path] = /tmp/ " ;
        $params["after-line"] = "[global]" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

}
