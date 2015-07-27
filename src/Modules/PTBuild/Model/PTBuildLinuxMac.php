<?php

Namespace Model;

class PTBuildLinuxMac extends BasePHPApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
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
        $this->postinstallCommands = $this->getPostInstallCommands();
        $this->programNameMachine = "ptbuild"; // command and app dir name
        $this->programNameFriendly = " PTBuild! "; // 12 chars
        $this->programNameInstaller = "PTBuild - Update to latest version";
        $this->programExecutorTargetPath = 'ptbuild/src/Bootstrap.php';
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
         * @todo create switching user -- user shell
         * @todo user can sudo without password -- first cm file step
         * @todo create default setting for switching user - how to do this? can we api settings changes
         * @todo sudo chmod 777 /opt/ptbuild/ptbuild/ptbuildvars -- second CM file step
         * @todo sudo mkdir /opt/ptbuild/pipes -- third CM file step
         * @todo sudo chmod 777 /opt/ptbuild/pipes-- fourth CM file step
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
        $path = dirname(dirname(__FILE__)).DS.'Scripts'.DS.'create-user.sh' ;
        $this->executeAsShell("sh $path");
        return $path ;
    }


}