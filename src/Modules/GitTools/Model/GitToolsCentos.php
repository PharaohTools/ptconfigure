<?php

Namespace Model;

class GitToolsCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "GitTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "git")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "git-core")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "gitk")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "git-cola")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "git")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "git-core")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "gitk")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "git-cola")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "gittools"; // command and app dir name
        $this->programNameFriendly = "!Git Tools!!"; // 12 chars
        $this->programNameInstaller = "Git Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array( "git", "gitk", "git-cola" )) ;
    }

}