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
//        $ray[]["method"] = array("object" => $this, "method" => "ensureApplicationUser", "params" => array() ) ;
        if (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) {
            $vhestring = '';
            $vheipport = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptbuild/pipes/" ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-pipes/pipes/* /opt/ptbuild/pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-keys/* /opt/ptbuild/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptbuild/keys/*" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/users.txt /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/ptbuildvars /opt/ptbuild/ptbuild/ptbuildvars" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R ptbuild:ptbuild /opt/ptbuild/" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptbuild/" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (is_dir(PIPEDIR)) {
            $ray[]["command"][] = "echo 'Create temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-keys/" ;
            $ray[]["command"][] = "echo 'Copy to temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/pipes /tmp/ptbuild-pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/keys /tmp/ptbuild-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/ptbuildvars /tmp/ptbuild-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt /tmp/ptbuild-settings/" ; }
        $this->preinstallCommands = $ray ;
        return $ray ;
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