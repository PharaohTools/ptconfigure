<?php

Namespace Model;

class JenkinsSudoNoPassLinuxMac extends BaseLinuxApp {

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
        $this->autopilotDefiner = "JenkinsSudoNoPass";
        $this->installCommands = array(
            array("command" => array(
                    'echo "The following will be written to /etc/sudoers" ',
                    'echo "Please check if it looks wrong" ',
                    'echo "It may break your system if wrong !!!" ',
                    'echo "jenkins ALL=NOPASSWD: ALL" ',
                    'echo "jenkins ALL=NOPASSWD: ALL" >> /etc/sudoers ' ) )
            );
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "jenkinssudonopass"; // command and app dir name
        $this->programNameFriendly = "Jenk Sudo Ps"; // 12 chars
        $this->programNameInstaller = "Sudo w/o Pass for Jenkins User";
        $this->initialize();
      }

}