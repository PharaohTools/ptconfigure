<?php

Namespace Model;

class RubyRVM extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "RubyRVM";
    $this->installCommands = array(
      "apt-get install -y libreadline6-dev libyaml-dev libsqlite3-dev sqlite3 ".
        "libxml2-dev libxslt1-dev bison libffi-dev libmysqlclient-dev " .
        "libmysql-ruby libgdbm-dev libncurses5-dev",
      "su ****INSTALL USER NAME****",
      "cd ****INSTALL USER HOME DIR****",
      "curl -L https://get.rvm.io | bash -s stable --ruby",
    );
    $this->uninstallCommands = array( "" );
    $this->programDataFolder = "/opt/ruby/";
    $this->programNameMachine = "ruby"; // command and app dir name
    $this->programNameFriendly = " !Ruby RVM!!"; // 12 chars
    $this->programNameInstaller = "Ruby RVM";
    $this->registeredPreInstallFunctions = array("askForInstallUserName",
      "askForInstallUserHomeDir");
    $this->initialize();
  }

}