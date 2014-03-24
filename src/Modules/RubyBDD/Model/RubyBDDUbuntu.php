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
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForInstallUserHomeDir", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "cucumber")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "capybara")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "calabash")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForInstallUserHomeDir", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "cucumber")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "capybara")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "calabash")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "ruby"; // command and app dir name
        $this->programNameFriendly = " !Ruby BDD!!"; // 12 chars
        $this->programNameInstaller = "Ruby BDD";
        $this->initialize();
    }

    public function askStatus() {
        $status1 = ($this->executeAndGetReturnCode("cucumber") == 0) ? true : false ;
        $status2 = ($this->executeAndGetReturnCode("capybara") == 0) ? true : false ;
        $status3 = ($this->executeAndGetReturnCode("calabash") == 0) ? true : false ;
        return ($status1 == true && $status2 == true && $status3 == true) ? true : false ;
    }

}