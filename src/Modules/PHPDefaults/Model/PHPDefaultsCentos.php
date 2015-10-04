<?php

Namespace Model;

class PHPDefaultsCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array( array("5.9", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
//    public $packages = array("php5-PHPDefaults" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", $this->packages ) ) ),
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
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($pax as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("PHP Module {$modToCheck} is not installed for this PHP installation.") ;
                $passing = false ; } }
        return $passing ;
    }

}
