<?php

Namespace Model;

class ApacheReverseProxyModulesUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "ApacheReverseProxyModules";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libapache2-mod-proxy-html")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "libxml2-dev")) ),
            array("command"=> "a2enmod proxy" ),
            array("command"=> "a2enmod proxy_http" ),
            array("command"=> "a2enmod proxy_ftp" ),
            array("command"=> "a2enmod proxy_connect" ),
            array("command"=> "a2enmod proxy_ajp" ),
            array("command"=> "a2enmod proxy_wstunnel" ),
            array("command"=> "a2enmod proxy_balancer" ),
            array("command"=> "a2enmod cache" ),
            array("command"=> "a2enmod headers" ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libapache2-mod-proxy-html")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "libxml2-dev")) ),
            array("command"=> "a2dismod proxy" ),
            array("command"=> "a2dismod proxy_http" ),
            array("command"=> "a2dismod proxy_ftp" ),
            array("command"=> "a2dismod proxy_connect" ),
            array("command"=> "a2dismod proxy_ajp" ),
            array("command"=> "a2dismod proxy_wstunnel" ),
            array("command"=> "a2dismod proxy_balancer" ),
            array("command"=> "a2dismod cache" ),
            array("command"=> "a2dismod headers" ),
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
            "proxy_http_module", "rewrite_module", "ssl_module" ) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("Apache Module {$modToCheck} does not exist.") ;
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