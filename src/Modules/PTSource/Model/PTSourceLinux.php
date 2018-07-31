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
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->programExecutorFolder = "/usr/bin";
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
            if (isset($this->params["enable-ssl"])) { $sslstring = ' --enable-ssl=true' ; }
            $ray[]["command"][] = SUDOPREFIX.PTSCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $cur_os_family = $this->findOSFamily() ;
            if ($cur_os_family === 'debian') {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptsource --fpm-port=6044 --is_debian --step-times --step-numbers ' ; }
            else if ($cur_os_family === 'redhat') {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptsource --fpm-port=6044 --is_redhat --step-times --step-numbers ' ; }
            else {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptsource --fpm-port=6044 --step-times --step-numbers ' ; }
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=ptsource --fpm-port=6044 --step-times --step-numbers '.$vhestring ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=source --fpm-port=6044  --step-times --step-numbers '.$sslstring ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("end").' --app-slug=ptsource --step-times --step-numbers' ;
            if (isset($this->params["enable-http"])) {
                if ($cur_os_family === 'debian') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getHTTPConfigureAutoPath().' --app-slug=ptsource --enable-http=true --is_debian --step-times --step-numbers ' ; }
                else if ($cur_os_family === 'redhat') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getHTTPConfigureAutoPath().' --app-slug=ptsource --enable-http=true --is_redhat --step-times --step-numbers ' ; } }
            if (isset($this->params["enable-ssh"])) {
                if ($cur_os_family === 'debian') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getSSHConfigureAutoPath().' --app-slug=ptsource --enable-ssh=true --is_debian --step-times --step-numbers ' ; }
                else if ($cur_os_family === 'redhat') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getSSHConfigureAutoPath().' --app-slug=ptsource --enable-ssh=true --is_redhat --step-times --step-numbers ' ; } }
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptsource/data/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptsource/repositories/" ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptsource-repositories/repositories/* /opt/ptsource/repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptsource-keys/* /opt/ptsource/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptsource/keys/*" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptsource-data/* /opt/ptsource/data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptsource-settings/ptsourcevars /opt/ptsource/ptsource/ptsourcevars" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R ptsource:ptsource /opt/ptsource/" ;
        $ray[]["command"][] = SUDOPREFIX."chown -R ptgit:ptsource /opt/ptsource/repositories/" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptsource/" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (is_dir(PIPEDIR)) {
            $ray[]["command"][] = "echo 'Create temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-data/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptsource-keys/" ;
            $ray[]["command"][] = "echo 'Copy to temp ptsource directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptsource/repositories /tmp/ptsource-repositories/" ;
//            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptsource/keys /tmp/ptsource-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptsource/ptsource/ptsourcevars /tmp/ptsource-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptsource/data/* /tmp/ptsource-data/" ; }
        $this->preinstallCommands = $ray ;
        return $ray ;
    }

    public function findOSFamily() {
        $sd = new \Model\SystemDetectionAllOS();
        $fam = $sd->distro ;
        if (in_array($fam, array('Ubuntu', 'Debian'))) {
            return 'debian' ;
        } else if (in_array($fam, array('Redhat', 'CentOS'))) {
            return 'redhat' ;
        } else {
            return false ;
        }
    }

    public function isS390xArch() {
        $sd = new \Model\SystemDetectionAllOS();
        $arch = $sd->architecture ;
        if ($arch === 's390x') {
            return true ;
        }
        return false ;
    }

    public function getDeployAutoPath() {
        $path = dirname(dirname(__DIR__)).DS.'PTWebApplication'.DS.'Autopilots'.DS.'PTDeploy'.DS.'create-vhost.php' ;
        return $path ;
    }

    public function getWebappConfigureAutoPath() {
        $path = dirname(dirname(__DIR__)).DS.'PTWebApplication'.DS.'Autopilots'.DS.'PTConfigure'.DS.'app-state-conf.dsl.php' ;
        return $path ;
    }

    public function getModuleConfigureAutoPath($type = "start") {
        $path = dirname(__DIR__).DS.'Autopilots'.DS.'PTConfigure'.DS.'app-conf-'.$type.'.dsl.php' ;
        return $path ;
    }

    public function getSSHConfigureAutoPath() {
        $path = dirname(__DIR__).DS.'Autopilots'.DS.'PTConfigure'.DS.'git-ssh.dsl.php' ;
        return $path ;
    }

    public function getHTTPConfigureAutoPath() {
        $path = dirname(__DIR__).DS.'Autopilots'.DS.'PTConfigure'.DS.'git-http.dsl.php' ;
        return $path ;
    }

}