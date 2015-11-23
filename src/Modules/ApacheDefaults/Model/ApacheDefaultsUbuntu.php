<?php

Namespace Model;

class ApacheDefaultsUbuntu extends BaseLinuxApp {

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
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "templateApacheDefaultsConfig", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/ApacheDefaults"; // command and app dir name
        $this->programNameMachine = "ApacheDefaults"; // command and app dir name
        $this->programNameFriendly = "Apache Defaults!"; // 12 chars
        $this->programNameInstaller = "Apache Default Settings";
        $this->initialize();
    }

    public function templateApacheDefaultsConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating Apache Defaults Configuration, ensuring our session save path of /tmp is set.", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/etc/php-ApacheDefaults.conf" ;
        $params["search"] = "php_admin_value[session.save_path] = /tmp/ " ;
        $params["after-line"] = "[global]" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

}
