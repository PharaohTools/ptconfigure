<?php

Namespace Model;

class LigHTTPDServer extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "LigHTTPDServer";
    $this->installCommands = array("apt-get install -y lighthttpd");
    $this->uninstallCommands = array("apt-get remove -y lighthttpd");
    $this->programDataFolder = "/opt/LigHTTPDServer"; // command and app dir name
    $this->programNameMachine = "lighttpdserver"; // command and app dir name
    $this->programNameFriendly = "LigHTTPD Server!"; // 12 chars
    $this->programNameInstaller = "LigHTTPD Server";
    $this->initialize();
  }

}