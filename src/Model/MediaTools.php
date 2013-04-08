<?php

Namespace Model;

class MediaTools extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "MediaTools";
    $this->installCommands = array( "apt-get install -y vlc" );
    $this->uninstallCommands = array( "apt-get remove -y vlc" );
    $this->programDataFolder = "/opt/MediaTools"; // command and app dir name
    $this->programNameMachine = "mediatools"; // command and app dir name
    $this->programNameFriendly = "Media Tools!"; // 12 chars
    $this->programNameInstaller = "Media Tools";
    $this->initialize();
  }

}