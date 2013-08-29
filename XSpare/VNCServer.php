<?php

Namespace Model;

class VNCServer extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "VNCServer";
    $this->installCommands = array(
        "cd /tmp" ,
        "git clone https://github.com/phpengine/jenkins-php-plugins jplugins",
        "rm -rf ****PROGDIR****",
        "mkdir -p ****PROGDIR****",
        "mv /tmp/jplugins/jenkins ****PROGDIR****",
        "chmod -R 775 ****PROGDIR****/*",
        "chown -R jenkins ****PROGDIR****",
        "rm -rf /tmp/jplugins",
        "service jenkins restart"
    );
    $this->uninstallCommands = array(
        "rm -rf ****PROGDIR****/*",
        "service jenkins restart"
    );
    $this->programDataFolder = "/var/lib/jenkins/plugins"; // command and app dir name
    $this->programNameMachine = "cleopatra-vncserver"; // command and app dir name
    $this->programNameFriendly = "!VNC Server!"; // 12 chars
    $this->programNameInstaller = "VNC Server";
    $this->initialize();
  }

}