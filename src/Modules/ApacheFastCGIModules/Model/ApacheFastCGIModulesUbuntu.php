<?php

Namespace Model;

class ApacheFastCGIModulesUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("12", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libapache2-mod-cgi")) ),
            array("method"=> array("object" => $this, "method" => "apacheReload", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libapache2-mod-cgi")) ),
            array("method"=> array("object" => $this, "method" => "apacheReload", "params" => array()))
        );
        $this->programDataFolder = "/opt/ApacheModules"; // command and app dir name
        $this->programNameMachine = "apachemodules"; // command and app dir name
        $this->programNameFriendly = "Apache Fast CGI Mods!"; // 12 chars
        $this->programNameInstaller = "Apache Fast CGI Modules";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = SUDOREFIX.'apachectl -t -D DUMP_MODULES';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $modsToCheck = array("deflate_module", "php5_module", "rewrite_module", "ssl_module" ) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("Apache Module {$modToCheck} does not exist.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

    public function apacheReload() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("apache2");
        $serviceManager->reload();
    }

}