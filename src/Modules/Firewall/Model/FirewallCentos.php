<?php

Namespace Model;

class FirewallCentos extends FirewallUbuntu {

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
        $this->autopilotDefiner = "Firewall";
        $this->actionsToMethods = $this->setActionsToMethods() ;
        $this->registeredPreInstallFunctions = array("ensurePython") ;
        $this->installCommands = array(
            "cd /usr/local/src",
            "wget https://launchpad.net/ufw/0.33/0.33/+download/ufw-0.33.tar.gz",
            "tar zxvf ufw-0.33.tar.gz",
            "cd ufw-0.33",
            "python ./setup.py install",
            "chmod -R g-w /etc/ufw /lib/ufw /etc/default/ufw /usr/local/sbin/ufw"
        );
        $this->uninstallCommands = array("yum remove -y ufw");
        $this->programDataFolder = "";
        $this->programNameMachine = "firewall"; // command and app dir name
        $this->programNameFriendly = "! Firewall !"; // 12 chars
        $this->programNameInstaller = "Firewall";
        $this->initialize();
    }

    protected function getUfw() {
    }

}