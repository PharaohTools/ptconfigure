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
        $this->postinstallCommands = $this->getPostInstallCommands();
        $this->programNameMachine = "pttrack"; // command and app dir name
        $this->programNameFriendly = " PTTrack! "; // 12 chars
        $this->programNameInstaller = "PTTrack - Update to latest version";
        $this->programExecutorTargetPath = 'pttrack/src/Bootstrap.php';
        $this->initialize();
    }

    public function getPostInstallCommands() {
        $ray = array( ) ;
        if (isset($this->params["with-webfaces"]) && $this->params["with-webfaces"]==true) {
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=".$this->getDeployAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX."sh ".$this->getUserShellAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTCCOMM." auto x --af=".$this->getConfigureAutoPath() ; }
        /*
         * @todo create switching user -- user shell DONE
         * @todo user can sudo without password -- first cm file step DONE
         * @todo create default setting for switching user - how to do this? can we api settings changes
         * @todo sudo chmod 777 /opt/ptbuild/ptbuild/ptbuildvars -- second CM file step DONE
         * @todo sudo mkdir /opt/ptbuild/pipes -- third CM file step DONE
         * @todo sudo chmod 777 /opt/ptbuild/pipes-- fourth CM file step DONE
         */
        return $ray ;
    }

    public function getDeployAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTDeploy'.DS.'create-vhost.php' ;
        return $path ;
    }

    public function getConfigureAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTConfigure'.DS.'users-and-permissions.php' ;
        return $path ;
    }

    public function getUserShellAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Scripts'.DS.'create-linux-user.sh' ;
        $this->executeAsShell("sh $path");
        return $path ;
    }

}