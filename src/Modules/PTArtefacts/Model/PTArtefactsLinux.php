<?php

Namespace Model;

class PTArtefactsLinux extends BasePHPApp {

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
        $this->autopilotDefiner = "PTArtefacts";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptartefacts.git",
              "ptartefacts",
              null // can be null for none
          )
        );
//        $this->postinstallCommands = $this->getLinuxPostInstallCommands();
        $this->programNameMachine = "ptartefacts"; // command and app dir name
        $this->programNameFriendly = " PTArtefacts! "; // 12 chars
        $this->programNameInstaller = "PTArtefacts - Update to latest version";
        $this->programExecutorTargetPath = 'ptartefacts/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
//        $ray[]["method"] = array("object" => $this, "method" => "ensureApplicationUser", "params" => array() ) ;
        if ( (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) ||
             (isset($this->params["guess"]) && $this->params["guess"]==true) ) {
            $vhestring = '--vhe-url=artefacts.pharaoh.tld';
            $vheipport = '--vhe-ip-port=127.0.0.1:80';
            $sslstring = '';
            if (isset($this->params["vhe-url"])) { $vhestring = '--vhe-url='.$this->params["vhe-url"] ; }
            if (isset($this->params["vhe-ip-port"])) { $vheipport = '--vhe-ip-port='.$this->params["vhe-ip-port"] ; }
            if (isset($this->params["enable-ssl"])) { $sslstring = ' --enable-ssl=true' ; }
            $ray[]["command"][] = SUDOPREFIX.PTSCOMM." assetpublisher publish --yes --guess" ;
//            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getLinuxUserShellAutoPath() ;
            $cur_os_family = $this->findOSFamily() ;
            if ($cur_os_family === 'debian') {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptartefacts --fpm-port=6045 --is_debian ' ; }
            else if ($cur_os_family === 'redhat') {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptartefacts --fpm-port=6045 --is_redhat ' ; }
            else {
                $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("start").' --app-slug=ptartefacts --fpm-port=6045 ' ; }
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getWebappConfigureAutoPath().' --app-slug=ptartefacts --fpm-port=6045 '.$vhestring ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath(). " $vhestring $vheipport".' --app-slug=artefacts --fpm-port=6045'.$sslstring ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getModuleConfigureAutoPath("end").' --app-slug=ptartefacts' ;
            if (isset($this->params["enable-http"])) {
                if ($cur_os_family === 'debian') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getHTTPConfigureAutoPath().' --app-slug=ptartefacts --enable-http=true --is_debian ' ; }
                else if ($cur_os_family === 'redhat') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getHTTPConfigureAutoPath().' --app-slug=ptartefacts --enable-http=true --is_redhat ' ; } }
            if (isset($this->params["enable-ssh"])) {
                if ($cur_os_family === 'debian') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getSSHConfigureAutoPath().' --app-slug=ptartefacts --enable-ssh=true --is_debian ' ; }
                else if ($cur_os_family === 'redhat') {
                    $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getSSHConfigureAutoPath().' --app-slug=ptartefacts --enable-ssh=true --is_redhat ' ; } }
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptartefacts/data/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /opt/ptartefacts/repositories/" ; }
        if (is_array($this->preinstallCommands) && count($this->preinstallCommands)>0) {
            $ray[]["command"][] = "echo 'Copy from temp ptartefacts directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptartefacts-repositories/repositories/* /opt/ptartefacts/repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /tmp/ptartefacts-keys/* /opt/ptartefacts/keys/" ;
            $ray[]["command"][] = SUDOPREFIX."chmod -R 0600 /opt/ptartefacts/keys/*" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptartefacts-data/* /opt/ptartefacts/data/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /tmp/ptartefacts-settings/ptartefactsvars /opt/ptartefacts/ptartefacts/ptartefactsvars" ; }
        $ray[]["command"][] = SUDOPREFIX."chown -R ptartefacts:ptartefacts /opt/ptartefacts/" ;
        $ray[]["command"][] = SUDOPREFIX."chown -R ptgit:ptartefacts /opt/ptartefacts/repositories/" ;
        $ray[]["command"][] = SUDOPREFIX."chmod -R 775 /opt/ptartefacts/" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (is_dir(PIPEDIR)) {
            $ray[]["command"][] = "echo 'Create temp ptartefacts directories'" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptartefacts-repositories/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptartefacts-data/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptartefacts-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."mkdir -p /tmp/ptartefacts-keys/" ;
            $ray[]["command"][] = "echo 'Copy to temp ptartefacts directories'" ;
            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptartefacts/repositories /tmp/ptartefacts-repositories/" ;
//            $ray[]["command"][] = SUDOPREFIX."cp -r /opt/ptartefacts/keys /tmp/ptartefacts-keys/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptartefacts/ptartefacts/ptartefactsvars /tmp/ptartefacts-settings/" ;
            $ray[]["command"][] = SUDOPREFIX."cp /opt/ptartefacts/data/* /tmp/ptartefacts-data/" ; }
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