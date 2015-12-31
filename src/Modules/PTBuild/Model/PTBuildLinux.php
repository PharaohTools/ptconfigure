<?php

Namespace Model;

class PTBuildLinux extends BasePHPApp {

    // Compatibility
    public $os = array("Linux") ;
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
//        $this->postinstallCommands = $this->getLinuxPostInstallCommands();
        $this->programNameMachine = "ptbuild"; // command and app dir name
        $this->programNameFriendly = " PTBuild! "; // 12 chars
        $this->programNameInstaller = "PTBuild - Update to latest version";
        $this->programExecutorTargetPath = 'ptbuild/src/Bootstrap.php';
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
        if (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) {
            $vhestring = '';
            $vheipport = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ; }
        $this->postinstallCommands = $ray ;
    }

    public function setupgradeinstallCommands() {
        $ray = array( ) ;
        if (isset($this->params["force"]) && $this->params["force"]==true) {
            $vhestring = '';
            $vheipport = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ;
            $ptb_user = PTBCOMM ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/pipes /tmp/ptbuild-pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/keys /tmp/ptbuild-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/ptbuildvars /tmp/ptbuild-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt /tmp/ptbuild-settings/" ;
//            $ray[]["method"] = array("object" => $this, "method" => "install", "params" => array("params", "jenkins") ) ;
            $ray[]["command"][] = SUDOPREFIX."ptconfigure ptbuild install -yg --with-webfaces --vhe-url=ptbuild.pharaohtools.com --vhe-ip-port=198.211.118.37:80" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-pipes/pipes/* /opt/ptbuild/pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-keys/* /opt/ptbuild/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptbuild/keys/*" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/users.txt /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/ptbuildvars /opt/ptbuild/ptbuild/ptbuildvars" ;
            $ray[]["command"][] = SUDOPREFIX."chown -R ".$ptb_user.":".$ptb_user." /opt/ptbuild/" ;

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

    public function getLinuxUserShellAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Scripts'.DS.'create-linux-user.sh' ;
        $this->executeAsShell("sh $path");
        return $path ;
    }


}