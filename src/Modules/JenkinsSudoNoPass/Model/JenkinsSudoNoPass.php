<?php

Namespace Model;

class JenkinsSudoNoPass extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "JenkinsSudoNoPass";
    $this->installCommands = array(
        'echo "The following will be written to /etc/sudoers" ',
        'echo "Please check if it looks wrong" ',
        'echo "It may break your system if wrong !!!" ',
        'echo "jenkins ALL=NOPASSWD: ALL" ',
        'echo "jenkins ALL=NOPASSWD: ALL" >> /etc/sudoers '
    );
    $this->uninstallCommands = array( "" );
    $this->programDataFolder = "";
    $this->programNameMachine = "jenkinssudonopass"; // command and app dir name
    $this->programNameFriendly = "Jenk Sudo Ps"; // 12 chars
    $this->programNameInstaller = "Sudo w/o Pass for Jenkins User";
    $this->initialize();
  }

  protected function setInstallCommandsWithNewUserName() {
  }

}