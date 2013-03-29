<?php

Namespace Model;

class Java extends BaseLinuxApp {

  public function __construct() {

    parent::__construct();
    $this->autopilotDefiner = "RubyRVM";
    $this->installCommands = array(
      "apt-get install -y libreadline6-dev libyaml-dev libsqlite3-dev sqlite3 ".
        "libxml2-dev libxslt1-dev bison libffi-dev libmysqlclient-dev " .
        "libmysql-ruby libgdbm-dev libncurses5-dev" ,
      "cd ****INSTALL USER HOME DIR****",
      "curl -L -o /tmp/rubyinstall.sh https://get.rvm.io",
      "chown ****INSTALL USER NAME**** /tmp/rubyinstall.sh ",
      "chmod 777 /tmp/rubyinstall.sh ",
      "chmod u+x /tmp/rubyinstall.sh ",
      "su ****INSTALL USER NAME**** -c'/tmp/rubyinstall.sh' ",
      "rm /tmp/rubyinstall.sh " );
    $this->uninstallCommands = array( "" );
    $this->programDataFolder = "";
    $this->programNameMachine = "ruby"; // command and app dir name
    $this->programNameFriendly = " !Ruby RVM!!"; // 12 chars
    $this->programNameInstaller = "Ruby RVM";
    $this->registeredPreInstallFunctions = array("askForInstallUserName",
      "askForInstallUserHomeDir");
    $this->registeredPreUnInstallFunctions = array("askForInstallUserName",
      "askForInstallUserHomeDir");
    $this->initialize();

    $this->autopilotDefiner = "JavaJDK";
    $this->installCommands = array(
      "sudo apt-get install libreadline6-dev libyaml-dev libsqlite3-dev sqlite3 libxml2-dev libxslt1-dev",
    );
    $this->uninstallCommands = array( "apt-get remove selenium" );
    $this->programDataFolder = "/opt/ruby/";
    $this->programNameMachine = "java"; // command and app dir name
    $this->programNameFriendly = "Oracle Java!"; // 12 chars
    $this->programNameInstaller = "Java!";
    $this->initialize();
  }

}