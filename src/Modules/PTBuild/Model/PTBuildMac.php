<?php

Namespace Model;

class PTBuildMac extends PTBuildLinux {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
//        $ray[]["method"] = array("object" => $this, "method" => "ensureApplicationUser", "params" => array() ) ;
        if ( (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) ||
            (isset($this->params["guess"]) && $this->params["guess"]==true) ) {
            $vhestring = '--vhe-url=build.pharaoh.tld';
            $vheipport = '--vhe-ip-port=127.0.0.1:80';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getMacUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getMacPortsAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath().' --app-slug=ptbuild --fpm-port=6041' ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=ptbuild --fpm-port=6041' ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=build --fpm-port=6041' ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptbuild/pipes/" ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptbuild directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-pipes/pipes/* /opt/ptbuild/pipes/ || true" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptbuild-keys/* /opt/ptbuild/keys/ || true" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptbuild/keys/* || true" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/users.txt /opt/ptbuild/ptbuild/src/Modules/Signup/Data/users.txt || true" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptbuild-settings/ptbuildvars /opt/ptbuild/ptbuild/ptbuildvars || true" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R ptbuild:ptbuild /opt/ptbuild/ || true" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptbuild/ || true" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function getMacUserShellAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Scripts'.DS.'create-mac-user.sh' ;
        $this->executeAsShell("sh $path");
        return $path ;
    }

    public function getMacPortsAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'macports.php' ;
        return $path ;
    }

}