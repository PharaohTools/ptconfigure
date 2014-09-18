<?php

Namespace Model;

class NetworkToolsCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("CentOS") ;
    public $versions = array("5", "6", "7") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "NetworkTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "net-tools")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "netstat")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "lsof")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "telnet")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "ps")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "traceroute")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "netstat")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "lsof")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "telnet")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "ps")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "networktools"; // command and app dir name
        $this->programNameFriendly = "!Network Tools!!"; // 12 chars
        $this->programNameInstaller = "Network Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array("traceroute", "netstat", "lsof",  "telnet", "ps")) ;
    }

}
