<?php

Namespace Model;

class ApacheReverseProxyModulesUbuntu14Plus extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("14.04", "14.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "ApacheReverseProxyModules";
        $mods  = " lbmethod_byrequests proxy proxy_http proxy_ftp proxy_connect proxy_ajp proxy_wstunnel"  ;
        $mods .= " proxy_balancer cache headers"  ;
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libapache2-mod-proxy-html")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libxml2-dev")) ),
            array("command"=> "a2enmod {$mods}" ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libapache2-mod-proxy-html")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libxml2-dev")) ),
            array("command"=> "a2dismod {$mods}" ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programDataFolder = "/opt/ApacheReverseProxyModules"; // command and app dir name
        $this->programNameMachine = "apachereverseproxymodules"; // command and app dir name
        $this->programNameFriendly = "Apache Proxy Mods!"; // 12 chars
        $this->programNameInstaller = "Apache Rev. Proxy Modules";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'apachectl -t -D DUMP_MODULES';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $modsToCheck = array( "http_module", "deflate_module", "php5_module", "proxy_module", "proxy_html_module",
            "rewrite_module", "ssl_module" ) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("Apache Module {$modToCheck} does not exist.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

    public function apacheRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("apache2");
        $serviceManager->restart();
    }

}