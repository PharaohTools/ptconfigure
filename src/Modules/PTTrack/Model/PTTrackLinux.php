<?php

Namespace Model;

class PTTrackLinux extends BasePHPApp {

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
        $this->autopilotDefiner = "PTTrack";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/pttrack.git",
              "pttrack",
              null // can be null for none
          )
        );
        $this->programNameMachine = "pttrack"; // command and app dir name
        $this->programNameFriendly = " PTTrack! "; // 12 chars
        $this->programNameInstaller = "PTTrack - Update to latest version";
        $this->programExecutorTargetPath = 'pttrack/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->programExecutorFolder = "/usr/bin";
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
        if ( (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) ||
            (isset($this->params["guess"]) && $this->params["guess"]==true) ) {
            $vhestring = '--vhe-url=track.pharaoh.tld';
            $vheipport = '--vhe-ip-port=127.0.0.1:80';
            $sslstring = '';
            if (isset($this->params["vhe-url"])) {
                $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) {
                $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            if (isset($this->params["enable-ssl"])) {
                $sslstring = ' --enable-ssl' ; }
            $ray[]["command"][] = SUDOPREFIX.PTTRCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath().' --app-slug=pttrack --fpm-port=6042' ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=pttrack --fpm-port=6042' ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=track --fpm-port=6042'.$sslstring ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp pttrack directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/pttrack-data/* /opt/pttrack/data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/pttrack-jobs/* /opt/pttrack/jobs/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/pttrack-settings/users.txt /opt/pttrack/pttrack/src/Modules/Signup/Data/users.txt" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/pttrack-settings/pttrackvars /opt/pttrack/pttrack/pttrackvars" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R pttrack:pttrack /opt/pttrack/" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/pttrack/" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (is_dir('/opt/pttrack/pttrack/')) {
            $ray[]["command"][] = "echo 'Create temp pttrack directories'" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/pttrack-jobs/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/pttrack-data/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/pttrack-settings/" ;
            $ray[]["command"][] = "echo 'Copy to temp pttrack directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/pttrack/jobs/* /tmp/pttrack-jobs/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/pttrack/data/* /tmp/pttrack-data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/pttrack/pttrack/pttrackvars /tmp/pttrack-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/pttrack/pttrack/src/Modules/Signup/Data/users.txt /tmp/pttrack-settings/" ; }
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