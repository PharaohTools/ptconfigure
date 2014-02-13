<?php

Namespace Model;

class UserUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "User";
        $this->registeredPreInstallFunctions = array("addUser", "removeUser");
        $this->installCommands = array("apt-get install -y user user-docutils");
        $this->uninstallCommands = array("apt-get remove -y user user-docutils");
        $this->programDataFolder = "";
        $this->programNameMachine = "user"; // command and app dir name
        $this->programNameFriendly = "!User!!"; // 12 chars
        $this->programNameInstaller = "User";
        $this->initialize();
    }

}