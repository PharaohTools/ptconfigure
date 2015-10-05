<?php

Namespace Model;

class PHPFPMMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $packages = array("php55-fpm") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("MacPorts", $this->packages ) ) ),
            array("method"=> array("object" => $this, "method" => "ensureFPMPoolDirectory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "ensureFPMLogFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "copyDefaultFPMConfig", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "templateFPMConfig", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "restartFPM", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("MacPorts", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPFPM"; // command and app dir name
        $this->programNameMachine = "PHPFPM"; // command and app dir name
        $this->programNameFriendly = "PHP FPM!"; // 12 chars
        $this->programNameInstaller = "PHP Fast Process Manager";
        $this->statusCommand = "exit 1" ; // "php-fpm -v";
        $this->initialize();
    }

    public function ensureFPMPoolDirectory() {
        $fpm_dir = '/etc/fpm.d/' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring existence of FPM Pool Directory", $this->getModuleName()) ;
        $comm = 'mkdir -p '.$fpm_dir ;
        $this->executeAndGetReturnCode($comm, true, true) ;
    }

    public function copyDefaultFPMConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Copying in default FPM Config File", $this->getModuleName()) ;
        $comm = 'cp /etc/php-fpm.conf.default /etc/php-fpm.conf' ;
        $this->executeAndGetReturnCode($comm, true, true) ;
    }

    public function ensureFPMLogFile() {
        $log_file = '/var/log/php-fpm.log' ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring existence of FPM Log File", $this->getModuleName()) ;
        $comm = 'touch '.$log_file ;
        $this->executeAndGetReturnCode($comm, true, true) ;
    }

    public function templateFPMConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Updating PHP FPM Configuration, ensuring our FPM Pool dir /etc/fpm.d/ is set.", $this->getModuleName()) ;
        $fileFactory = new \Model\File() ;
        $params = $this->params ;
        $params["file"] = "/etc/php-fpm.conf" ;
        $params["search"] = "include=/etc/fpm.d/*.conf" ;
        $params["after-line"] = "; FPM Configuration ;" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
//        $logging->log("Updating PHP FPM Configuration, ensuring our error log of /var/log/php-fpm.log is set.", $this->getModuleName()) ;
//        $params = $this->params ;
//        $params["file"] = "/etc/php-fpm.conf" ;
//        $params["search"] = "" ;
//        $params["after-line"] = "[global]" ;
//        $file = $fileFactory->getModel($params) ;
//        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

    public function restartFPM() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Stopping any running PHP FPM Processes", $this->getModuleName()) ;
        $comm = 'pkill php-fpm' ;
        $res[] = $this->executeAndGetReturnCode($comm, true, true) ;
        $logging->log("Starting PHP FPM Processes", $this->getModuleName()) ;
        $comm = 'php-fpm' ;
        $res[] = $this->executeAndGetReturnCode($comm, true, true) ;
        return in_array(false, $res)==false ;
    }

}
