<?php

Namespace Model;

class Java extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
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