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
            array("method"=> array("object" => $this, "method" => "templateCopyPHPModLoader", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "templateApachePHPDefaultsConfig", "params" => array()) ),
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
        $params1 = $params2 = $params3 = $params4 = $this->params ;
        $params1["file"] = "/etc/apache2/httpd.conf" ;
        $params1["search"] = "# LoadModule php5_module libexec/apache2/libphp5.so" ;
        $file = $fileFactory->getModel($params1) ;
        $res[] = $file->performShouldNotHaveLine();
        $params2["file"] = "/etc/apache2/httpd.conf" ;
        $params2["search"] = "#LoadModule php5_module libexec/apache2/libphp5.so" ;
        $file = $fileFactory->getModel($params2) ;
        $res[] = $file->performShouldNotHaveLine();
        $params3["file"] = "/etc/apache2/httpd.conf" ;
        $params3["search"] = "LoadModule php5_module libexec/apache2/libphp5.so" ;
        $params3["after-line"] = "# LoadModule foo_module modules/mod_foo.so" ;
        $file = $fileFactory->getModel($params3) ;
        $res[] = $file->performShouldHaveLine();
        $params4["file"] = "/etc/apache2/httpd.conf" ;
        $params4["search"] = "LoadModule php5_module libexec/apache2/libphp5.so" ;
        $params4["after-line"] = "LoadModule php5_module libexec/apache2/libphp5.so" ;
        $file = $fileFactory->getModel($params4) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

    public function templateCopyPHPModLoader() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $copyFactory = new \Model\Copy() ;
        $logging->log("Copying in apache php module config", $this->getModuleName()) ;
        $params1 = $params2 = $params3 = $params4 = $this->params ;
        $params1["source"] = dirname(dirname(__FILE__))."/Templates/mod_php5.conf" ;
        $params1["target"] = "/etc/apache2/extra/php.conf" ;
        $copy = $copyFactory->getModel($params1) ;
        $res[] = $copy->performCopyPut();
        return in_array(false, $res)==false ;
    }

    public function templateApachePHPDefaultsConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating Apache PHP Module Default Configuration", $this->getModuleName()) ;
        $params1 = $params2 = $params3 = $params4 = $this->params ;
        $params1["file"] = "/etc/apache2/httpd.conf" ;
        $params1["search"] = "Include /etc/apache2/extra/php.conf" ;
        $file = $fileFactory->getModel($params1) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }



}