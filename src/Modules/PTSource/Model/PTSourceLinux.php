<?php

Namespace Model;

class PTSourceLinux extends BasePHPApp {

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
        $this->autopilotDefiner = "PTSource";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptsource.git",
              "ptsource",
              null // can be null for none
          )
        );
//        $this->postinstallCommands = $this->getLinuxPostInstallCommands();
        $this->programNameMachine = "ptsource"; // command and app dir name
        $this->programNameFriendly = " PTSource! "; // 12 chars
        $this->programNameInstaller = "PTSource - Update to latest version";
        $this->programExecutorTargetPath = 'ptsource/src/Bootstrap.php';
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
//        $ray[]["method"] = array("object" => $this, "method" => "ensureApplicationUser", "params" => array() ) ;
        if ( (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) ||
             (isset($this->params["guess"]) && $this->params["guess"]==true) ) {
            $vhestring = '--vhe-url=source.pharaoh.tld';
            $vheipport = '--vhe-ip-port=127.0.0.1:80';
            $sslstring = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            if (isset($this->params["enable-ssl"])) { $sslstring = ' --enable-ssl' ; }
            $ray[]["command"][] = SUDOPREFIX.PTSCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptsource --fpm-port=6044' ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=ptsource --fpm-port=6044 '.$vhestring ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=source --fpm-port=6044'.$sslstring ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("end").' '.$sslstring ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptsource/repositories/" ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptsource-repositories/repositories/* /opt/ptsource/repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptsource-keys/* /opt/ptsource/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptsource/keys/*" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptsource-settings/users.txt /opt/ptsource/ptsource/src/Modules/Signup/Data/users.txt" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptsource-settings/ptsourcevars /opt/ptsource/ptsource/ptsourcevars" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R ptsource:ptsource /opt/ptsource/" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptsource/" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (is_dir(PIPEDIR)) {
            $ray[]["command"][] = "echo 'Create temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-keys/" ;
            $ray[]["command"][] = "echo 'Copy to temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptsource/repositories /tmp/ptsource-repositories/" ;
//            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptsource/keys /tmp/ptsource-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptsource/ptsource/ptsourcevars /tmp/ptsource-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptsource/ptsource/src/Modules/Signup/Data/users.txt /tmp/ptsource-settings/" ; }
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

    public function getModuleConfigureAutoPath($type = "start") {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'app-conf-'.$type.'.dsl.php' ;
        return $path ;
    }

}