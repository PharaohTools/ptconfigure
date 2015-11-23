<?php

Namespace Model;

class PHPDefaultsMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
//    public $packages = array("php55-PHPDefaults") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("MacPorts", $this->packages ) ) ),
//            array("method"=> array("object" => $this, "method" => "ensurePHPDefaultsPoolDirectory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "ensurePHPDefaultsLogFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "templatePHPDefaultsConfig", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "restartPHPDefaults", "params" => array()) ),
        );
        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("MacPorts", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPDefaults"; // command and app dir name
        $this->programNameMachine = "PHPDefaults"; // command and app dir name
        $this->programNameFriendly = "PHP Defaults!"; // 12 chars
        $this->programNameInstaller = "PHP Default Settings";
        $this->statusCommand = "exit 1" ; // "php-PHPDefaults -v";
        $this->initialize();
    }

    public function ensurePHPDefaultsPoolDirectory() {
        $PHPDefaults_dir = '/etc/PHPDefaults.d/' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring existence of PHPDefaults Pool Directory", $this->getModuleName()) ;
        $comm = 'mkdir -p '.$PHPDefaults_dir ;
        $this->executeAndGetReturnCode($comm, true, true) ;
    }

    public function ensurePHPDefaultsLogFile() {
        $log_file = '/var/log/php_errors.log' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring existence of PHP Default Log File", $this->getModuleName()) ;
        $comm = 'touch '.$log_file ;
        $this->executeAndGetReturnCode($comm, true, true) ;
    }

    public function templatePHPDefaultsConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Updating PHP Defaults Configuration, ensuring our Default Error Log is set.", $this->getModuleName()) ;
        $fileFactory = new \Model\File() ;
        $params = $this->params ;
        $params["file"] = "/etc/php.ini" ;
        $params["search"] = "error_log = /var/log/php_errors.log" ;
        $params["after-line"] = "; http://php.net/error-log" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
//        $logging->log("Updating PHP Defaults Configuration, ensuring our session save path of /tmp is set.", $this->getModuleName()) ;
//        $params = $this->params ;
//        $params["file"] = "/etc/php-PHPDefaults.conf" ;
//        $params["search"] = "php_admin_value[session.save_path] = /tmp/ " ;
//        $params["after-line"] = "[global]" ;
//        $file = $fileFactory->getModel($params) ;
//        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

}
