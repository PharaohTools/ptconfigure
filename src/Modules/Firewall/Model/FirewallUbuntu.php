<?php

Namespace Model;

class FirewallUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $firewallRule ;
    protected $actionsToMethods ;

    public function __construct($params) {
        parent::__construct($params);
        $this->actionsToMethods = $this->setActionsToMethods() ;
        $this->autopilotDefiner = "Firewall" ;
        $this->installCommands = array("apt-get install -y ufw") ;
        $this->uninstallCommands = array("apt-get remove -y ufw") ;
        $this->programDataFolder = "" ;
        $this->programNameMachine = "firewall" ; // command and app dir name
        $this->programNameFriendly = "!Firewall!!" ; // 12 chars
        $this->programNameInstaller = "Firewall" ;
        $this->statusCommand = "command -v ufw" ;
        $this->initialize();
    }

    protected function performFirewallEnable() {
        return $this->enable();
    }

    protected function performFirewallDisable() {
        return $this->disable();
    }

    protected function performFirewallAllow() {
        $this->setFirewallRule();
        return $this->allow();
    }

    protected function performFirewallDeny() {
        $this->setFirewallRule();
        return $this->deny();
    }

    protected function performFirewallReject() {
        $this->setFirewallRule();
        return $this->reject();
    }

    protected function performFirewallLimit() {
        $this->setFirewallRule();
        return $this->limit();
    }

    protected function performFirewallDelete() {
        $this->setFirewallRule();
        return $this->delete();
    }

    protected function performFirewallInsert() {
        $this->setFirewallRule();
        return $this->insert();
    }

    protected function performFirewallReset() {
        return $this->reset();
    }

    public function setFirewallRule() {
        if (isset($this->params["firewall-rule"])) {
            $firewallRule = $this->params["firewall-rule"]; }
        else {
            $firewallRule = self::askForInput("Enter Firewall Rule:", true); }
        $this->firewallRule = $firewallRule ;
    }


    public function enable() {
        $out = $this->executeAndOutput("sudo ufw enable");
        if (strpos($out, "enabled") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Enable command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function disable() {
        $out = $this->executeAndOutput("sudo ufw endisable");
        if (strpos($out, "disabled") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Disable command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function allow() {
        $out = $this->executeAndOutput("sudo ufw allow $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Allow command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function deny() {
        $out = $this->executeAndOutput("sudo ufw deny $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Deny command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function reject() {
        $out = $this->executeAndOutput("sudo ufw reject $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Reject command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function limit() {
        $out = $this->executeAndOutput("sudo ufw limit $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Limit command did not execute correctly") ;
            return false ; }
        return true ;
    }


    public function delete() {
        $out = $this->executeAndOutput("sudo ufw delete $this->firewallRule");
        if (strpos($out, "Could not delete non-existent rule") != false ||
            strpos($out, "Rule deleted") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Delete command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function insert() {
        $out = $this->executeAndOutput("sudo ufw insert $this->firewallRule");
        if (strpos($out, "Skipping inserting existing rule") != false ||
            strpos($out, "Rule inserted") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Insert command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function reset() {
        $out = $this->executeAndOutput("echo y | sudo ufw reset --force $this->firewallRule");
        if (strpos($out, "Resetting all rules to installed defaults") != false ) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Firewall Reset command did not execute correctly") ;
            return false ; }
        return true ;
    }

    protected function setActionsToMethods() {
        return array(
            "enable" => "performFirewallEnable",
            "disable" => "performFirewallDisable",
            "allow" => "performFirewallAllow",
            "deny" => "performFirewallDeny",
            "reject" => "performFirewallReject",
            "limit" => "performFirewallLimit",
            "delete" => "performFirewallDelete",
            "insert" => "performFirewallInsert",
            "reset" => "performFirewallReset",
        ) ;
    }

    public function ensurePython() {
        $pythonFactory = new Python();
        $pythonModel = $pythonFactory->getModel($this->params) ;
        $pythonModel->ensureInstalled();
    }

}