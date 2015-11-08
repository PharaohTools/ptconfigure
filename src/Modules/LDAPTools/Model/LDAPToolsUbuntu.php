<?php

Namespace Model;

class LDAPToolsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "LDAPTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "slapd")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "ldap-utils")) ),
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "gitk")) ),
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "git-cola")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "slapd")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "ldap-utils")) ),
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "gitk")) ),
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "git-cola")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "LDAPTools"; // command and app dir name
        $this->programNameFriendly = "!LDAP Tools!!"; // 12 chars
        $this->programNameInstaller = "LDAP Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array( "slapd", "ldap-utils" )) ;
    }

}