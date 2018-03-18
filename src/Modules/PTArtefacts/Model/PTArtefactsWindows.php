<?php

Namespace Model;

class PTArtefactsWindows extends BasePHPWindowsApp {

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
        $this->autopilotDefiner = "PTArtefacts";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptartefacts.git",
              "ptartefacts",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptartefacts"; // command and app dir name
        $this->programNameFriendly = " PTArtefacts! "; // 12 chars
        $this->programNameInstaller = "PTArtefacts - Update to latest version";
        $this->programExecutorTargetPath = 'ptartefacts/src/Bootstrap.php';
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
            $ray[]["command"][] = "net user ptartefacts ptartefacts /ADD" ;
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
            $ray[]["command"][] = "net user ptartefacts ptartefacts /ADD" ;
            $ray[]["command"][] = PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ;
            $ptb_user = PTBCOMM ;
            $temp_dir = getenv('TEMP') ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptartefacts-pipes".DS."\" mkdir \"".$temp_dir."ptartefacts-pipes".DS."\"" ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptartefacts-keys".DS."\" mkdir \"".$temp_dir."ptartefacts-keys".DS."\"" ; //"mkdir -p ".$temp_dir."ptartefacts-settings".DS ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptartefacts-settings".DS."\" mkdir \"".$temp_dir."ptartefacts-settings".DS."\"" ; //"mkdir -p ".$temp_dir."ptartefacts-keys".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PIPEDIR." ".$temp_dir."ptartefacts-pipes".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PFILESDIR."keys ".$temp_dir."ptartefacts-keys".DS ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptartefacts".DS."ptartefacts".DS."ptartefactsvars ".$temp_dir."ptartefacts-settings".DS."ptartefactsvars" ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptartefacts".DS."ptartefacts".DS."src\\Modules\\Signup\\Data\\users.txt ".$temp_dir."ptartefacts-settings".DS."users.txt" ;
//            $ray[]["method"] = array("object" => $this, "method" => "install", "params" => array("params", "jenkins") ) ;
            $ray[]["command"][] = "ptconfigure ptartefacts install -yg --with-webfaces --vhe-url=ptartefacts.local --vhe-ip-port=127.0.0.1" ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptartefacts-pipes".DS."pipes".DS."* ".PIPEDIR ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptartefacts-keys".DS."* ".PFILESDIR."keys".DS ;
//            $ray[]["command"][] = "chmod -R 0600 /opt/ptartefacts/keys/*" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptartefacts-settings".DS."users.txt ".PFILESDIR."ptartefacts".DS."ptartefacts".DS."src\\Modules\\Signup\\Data\\users.txt" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptartefacts-settings".DS."ptartefactsvars ".PFILESDIR."ptartefacts".DS."ptartefacts".DS."ptartefactsvars" ;
//            $ray[]["command"][] = "chown -R ".$ptb_user.":".$ptb_user." /opt/ptartefacts".DS ;

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