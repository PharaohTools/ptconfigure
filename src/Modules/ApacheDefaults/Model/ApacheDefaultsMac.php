<?php

Namespace Model;

class ApacheDefaultsMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
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
        $this->statusCommand = "exit 1" ;
        $this->initialize();
    }

    public function templateApacheDefaultsConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating Apache Default Configuration, ensuring Apache loads the PHP Module", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/etc/apache2/httpd.conf" ;
        $params["search"] = "LoadModule php5_module libexec/apache2/libphp5.so" ;
        $params["after-line"] = "# LoadModule foo_module modules/mod_foo.so" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

}
