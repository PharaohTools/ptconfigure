<?php

Namespace Model;

class MysqlAdmins extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "MysqlAdmins";
    $this->installCommands = array(
      "cd ****INSTALL USER HOME DIR****",
      "curl -L -o /tmp/rubyinstall.sh https://get.rvm.io",
      "chown ****INSTALL USER NAME**** /tmp/rubyinstall.sh ",
      "chmod 777 /tmp/rubyinstall.sh ",
      "chmod u+x /tmp/rubyinstall.sh ",
      "su ****INSTALL USER NAME**** -c'/tmp/rubyinstall.sh' ",
      "rm /tmp/rubyinstall.sh " );
    $this->uninstallCommands = array( "" );
    $this->programDataFolder = "";
    $this->programNameMachine = "mysqladmins-cleopatra"; // command and app dir name
    $this->programNameFriendly = "MySQL Admins !"; // 12 chars
    $this->programNameInstaller = "MySQL Admin Users";
    $this->registeredPreInstallFunctions = array("askForMysqlRootUserName",
      "askForMysqlRootPassword");
    $this->registeredPreUnInstallFunctions = array("askForMysqlRootUserName",
      "askForMysqlRootPassword");
    $this->initialize();
  }

}