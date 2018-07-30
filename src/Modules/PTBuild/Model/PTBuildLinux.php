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
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
//        $ray[]["method"] = array("object" => $this, "method" => "ensureApplicationUser", "params" => array() ) ;
        if ( (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) ||
            (isset($this->params["guess"]) && $this->params["guess"]==true) ) {
            $vhestring = '--vhe-url=build.pharaoh.tld';
            $vheipport = '--vhe-ip-port=127.0.0.1:80';
            $sslstring = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            if (isset($this->params["enable-ssl"])) { $sslstring = ' --enable-ssl' ; }
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath().' --app-slug=ptbuild --fpm-port=6041' ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=ptbuild --fpm-port=6041' ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=build --fpm-port=6041'.$sslstring ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptbuild/pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptbuild/data/" ;}
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-pipes/pipes/* /opt/ptbuild/pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-keys/* /opt/ptbuild/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-data/* /opt/ptbuild/data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/users.txt /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/ptbuildvars /opt/ptbuild/ptbuild/ptbuildvars" ; }
        if (!isset($this->params["no-permissions"])) {
            $ray[]["command"][] = SUDOPREFIX."chown -R ptbuild:ptbuild /opt/ptbuild/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptbuild/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptbuild/keys/*" ;
        }
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
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptbuild-data/" ;
            $ray[]["command"][] = "echo 'Copy to temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/pipes /tmp/ptbuild-pipes/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/keys /tmp/ptbuild-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptbuild/data/* /tmp/ptbuild-data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/ptbuildvars /tmp/ptbuild-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt /tmp/ptbuild-settings/" ; }
        $this->preinstallCommands = $ray ;
        return $ray ;
    }

    public function getDeployAutoPath() {
        $path = dirname(dirname(dirname(__FILE__))).DS.'PTWebApplication'.DS.'Autopilots'.DS.'PTDeploy'.DS.'create-vhost.php' ;
        return $path ;
    }

    public function getWebappConfigureAutoPath() {
        $path = dirname(dirname(dirname(__FILE__))).DS.'PTWebApplication'.DS.'Autopilots'.DS.'PTConfigure'.DS.'app-state-conf.dsl.php' ;
        return $path ;
    }

    public function getModuleConfigureAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'app-conf.php' ;
        return $path ;
    }

}