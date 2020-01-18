<?php

Namespace Model;

class PTBuildWindows extends BasePHPWindowsApp {

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
        $this->autopilotDefiner = "PTBuild";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptbuild.git",
              "ptbuild",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptbuild"; // command and app dir name
        $this->programNameFriendly = " PTBuild! "; // 12 chars
        $this->programNameInstaller = "PTBuild - Update to latest version";
        $this->programExecutorTargetPath = 'ptbuild/src/Bootstrap.php';
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
            $ray[]["command"][] = "net user ptbuild ptbuild /ADD" ;
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
            $ray[]["command"][] = "net user ptbuild ptbuild /ADD" ;
            $ray[]["command"][] = PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ;
            $ptb_user = PTBCOMM ;
            $temp_dir = getenv('TEMP') ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptbuild-pipes".DS."\" mkdir \"".$temp_dir."ptbuild-pipes".DS."\"" ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptbuild-keys".DS."\" mkdir \"".$temp_dir."ptbuild-keys".DS."\"" ; //"mkdir -p ".$temp_dir."ptbuild-settings".DS ;
            $ray[]["command"][] = "if not exist \"".$temp_dir."ptbuild-settings".DS."\" mkdir \"".$temp_dir."ptbuild-settings".DS."\"" ; //"mkdir -p ".$temp_dir."ptbuild-keys".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PIPEDIR." ".$temp_dir."ptbuild-pipes".DS ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".PFILESDIR."keys ".$temp_dir."ptbuild-keys".DS ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptbuild".DS."ptbuild".DS."ptbuildvars ".$temp_dir."ptbuild-settings".DS."ptbuildvars" ;
            $ray[]["command"][] = "copy ".PFILESDIR."ptbuild".DS."ptbuild".DS."src\\Modules\\Signup\\Data\\users.txt ".$temp_dir."ptbuild-settings".DS."users.txt" ;
//            $ray[]["method"] = array("object" => $this, "method" => "install", "params" => array("params", "jenkins") ) ;
            $ray[]["command"][] = "ptconfigure ptbuild install -yg --with-webfaces --vhe-url=ptbuild.local --vhe-ip-port=127.0.0.1" ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptbuild-pipes".DS."pipes".DS."* ".PIPEDIR ;
            $ray[]["command"][] = "xcopy /q /s /e /y ".$temp_dir."ptbuild-keys".DS."* ".PFILESDIR."keys".DS ;
//            $ray[]["command"][] = "chmod -R 0600 /opt/ptbuild/keys/*" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptbuild-settings".DS."users.txt ".PFILESDIR."ptbuild".DS."ptbuild".DS."src\\Modules\\Signup\\Data\\users.txt" ;
            $ray[]["command"][] = "copy ".$temp_dir."ptbuild-settings".DS."ptbuildvars ".PFILESDIR."ptbuild".DS."ptbuild".DS."ptbuildvars" ;
//            $ray[]["command"][] = "chown -R ".$ptb_user.":".$ptb_user." /opt/ptbuild".DS ;

        }

        $this->upgradeinstallCommands = $ray ;
    }

    public function getDeployAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'create-vhost.php' ;
        return $path ;
    }

    public function getConfigureAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'ptbconf.php' ;
        return $path ;
    }

}