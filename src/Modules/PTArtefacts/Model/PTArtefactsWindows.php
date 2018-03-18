<?php

Namespace Model;

class PTSourceWindows extends BasePHPWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PTSource";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptsource.git",
              "ptsource",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptsource"; // command and app dir name
        $this->programNameFriendly = " PTSource! "; // 12 chars
        $this->programNameInstaller = "PTSource - Update to latest version";
        $this->programExecutorTargetPath = 'ptsource/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
        if (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) {
            $vhestring = '';
            $vheipport = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = "net user ptsource ptsource /ADD" ;
            $ray[]["command"][] = PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ; }
        $this->postinstallCommands = $ray ;
    }

    public function setupgradeinstallCommands() {
        $ray = array( ) ;
        if (isset($this->params["force"]) && $this->params["force"]==true) {
            $vhestring = '';
            $vheipport = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = "net user ptsource ptsource /ADD" ;
            $ray[]["command"][] = PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ;
            $ptb_user = PTBCOMM ;
            $temp_dir = getenv('TEMP') ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptsource-pipes".DS."\" mkdir \"".$temp_dir."ptsource-pipes".DS."\"" ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptsource-keys".DS."\" mkdir \"".$temp_dir."ptsource-keys".DS."\"" ; //"mkdir -p ".$temp_dir."ptsource-settings".DS ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptsource-settings".DS."\" mkdir \"".$temp_dir."ptsource-settings".DS."\"" ; //"mkdir -p ".$temp_dir."ptsource-keys".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PIPEDIR." ".$temp_dir."ptsource-pipes".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PFILESDIR."keys ".$temp_dir."ptsource-keys".DS ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptsource".DS."ptsource".DS."ptsourcevars ".$temp_dir."ptsource-settings".DS."ptsourcevars" ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptsource".DS."ptsource".DS."src\\Modules\\Signup\\Data\\users.txt ".$temp_dir."ptsource-settings".DS."users.txt" ;
//            $ray[]["method"] = array("object" => $this, "method" => "install", "params" => array("params", "jenkins") ) ;
            $ray[]["command"][] = "ptconfigure ptsource install -yg --with-webfaces --vhe-url=ptsource.local --vhe-ip-port=127.0.0.1" ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptsource-pipes".DS."pipes".DS."* ".PIPEDIR ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptsource-keys".DS."* ".PFILESDIR."keys".DS ;
//            $ray[]["command"][] = "chmod -R 0600 /opt/ptsource/keys/*" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptsource-settings".DS."users.txt ".PFILESDIR."ptsource".DS."ptsource".DS."src\\Modules\\Signup\\Data\\users.txt" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptsource-settings".DS."ptsourcevars ".PFILESDIR."ptsource".DS."ptsource".DS."ptsourcevars" ;
//            $ray[]["command"][] = "chown -R ".$ptb_user.":".$ptb_user." /opt/ptsource".DS ;

        }

        $this->upgradeinstallCommands = $ray ;
    }

    public function getDeployAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTDeploy'.DS.'create-vhost.php' ;
        return $path ;
    }

    public function getConfigureAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'ptbconf.php' ;
        return $path ;
    }

}