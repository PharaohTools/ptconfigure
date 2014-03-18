<?php

Namespace Model;

class RubyBDDUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "RubyBDD";
        $this->installCommands = array(
            "gem install cucumber" ,
            "gem install capybara",
            "gem install calabash" );
        $this->uninstallCommands = array(
            "gem uninstall cucumber" ,
            "gem uninstall capybara",
            "gem uninstall calabash" );
        $this->programDataFolder = "";
        $this->programNameMachine = "ruby"; // command and app dir name
        $this->programNameFriendly = " !Ruby BDD!!"; // 12 chars
        $this->programNameInstaller = "Ruby BDD";
        $this->registeredPreInstallFunctions = array("askForInstallUserName", "askForInstallUserHomeDir");
        $this->registeredPreUnInstallFunctions = array("askForInstallUserName", "askForInstallUserHomeDir");
        $this->initialize();
    }

    public function askStatus() {
        $status1 = ($this->executeAndGetReturnCode("cucumber") == 0) ? true : false ;
        $status2 = ($this->executeAndGetReturnCode("capybara") == 0) ? true : false ;
        $status3 = ($this->executeAndGetReturnCode("calabash") == 0) ? true : false ;
        return ($status1 == true && $status2 == true && $status3 == true) ? true : false ;
    }

}