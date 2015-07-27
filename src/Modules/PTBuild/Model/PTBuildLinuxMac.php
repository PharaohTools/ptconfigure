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
            $dapperAuto = $this->getDapperAutoPath() ;
            $ray[]["command"][] = SUDOPREFIX.PTBCOMM." assetpublisher publish --yes --guess" ;
            $ray[]["command"][] = SUDOPREFIX.PTDCOMM." auto x --af=$dapperAuto" ; }


        /*
         * @todo sudo chmod 777 /opt/ptbuild/ptbuild/ptbuildvars
         * @todo sudo mkdir /opt/ptbuild/pipes
         * @todo sudo chmod 777 /opt/ptbuild/pipes
         * @todo create switching user
         * @todo create default setting for switching user
         *
         */

        return $ray ;
    }

    public function getDapperAutoPath() {
        $path = dirname(dirname(__FILE__)).DS.'Autopilots'.DS.'PTDeploy'.DS.'create-vhost.php' ;
        return $path ;
    }


}