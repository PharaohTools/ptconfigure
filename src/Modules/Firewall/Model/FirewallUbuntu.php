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
    protected $defaultPolicy ;
    protected $firewallRule ;
    protected $actionsToMethods ;

    public function __construct($params) {
        parent::__construct($params);
        $this->actionsToMethods = $this->setActionsToMethods() ;
        $this->autopilotDefiner = "Firewall" ;
        $this->installCommands = array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "ufw")) ) ;
        $this->uninstallCommands = array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "ufw")) ) ;
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
        return $this->deleteRule();
    }

    protected function performFirewallInsert() {
        $this->setFirewallRule();
        return $this->insert();
    }

    protected function performFirewallReset() {
        return $this->resetRule();
    }

    protected function setFirewallDefault() {
        $this->setDefaultPolicyParam();
        return $this->setDefault();
    }

    public function reloadFirewall() {
        if ($this->disable()==false) {return false;}
        if ($this->enable()==false) {return false;}
        return true;
    }

    public function setFirewallRule() {
        if (isset($this->params["firewall-rule"])) {
            $firewallRule = $this->params["firewall-rule"]; }
        else {
            $firewallRule = self::askForInput("Enter Firewall Rule:", true); }
        $this->firewallRule = $firewallRule ;
    }

    public function setDefaultPolicyParam() {
        $opts =  array("allow", "deny", "reject") ;
        if (isset($this->params["policy"]) && in_array($this->params["policy"], $opts)) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Policy param for set default must be allow, deny or reject", $this->getModuleName()) ;
            $defaultPolicy = $this->params["policy"]; }
        else {
            $defaultPolicy = self::askForArrayOption("Enter Policy:", $opts, true); }
        $this->defaultPolicy = $defaultPolicy ;
    }

    public function enable() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw --force enable");
        if (strpos($out, "enabled") == false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Enable command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function disable() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw disable");
        if (strpos($out, "disabled") == false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Disable command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function allow() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw allow $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Allow command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function deny() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw deny $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Deny command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function reject() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw reject $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Reject command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function limit() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw limit $this->firewallRule");
        if (strpos($out, "Skipping adding existing rule") != false ||
            strpos($out, "Rule added") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Limit command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }


    public function deleteRule() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw delete $this->firewallRule");
        if (strpos($out, "Could not delete non-existent rule") != false ||
            strpos($out, "Rule deleted") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Delete command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function insert() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw insert $this->firewallRule");
        if (strpos($out, "Skipping inserting existing rule") != false ||
            strpos($out, "Rule inserted") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Insert command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function resetRule() {
        $out = $this->executeAndOutput("echo y | ".SUDOPREFIX." ufw reset --force $this->firewallRule");
        if (strpos($out, "Resetting all rules to installed defaults") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Reset command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function setDefault() {
        $out = $this->executeAndOutput(SUDOPREFIX."ufw default $this->defaultPolicy");
        if (strpos($out, "Default incoming policy changed to '{$this->defaultPolicy}'") != false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Firewall Reset command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    protected function setActionsToMethods() {
        return array(
            "enable" => "performFirewallEnable",
            "reload" => "reloadFirewall",
            "disable" => "performFirewallDisable",
            "allow" => "performFirewallAllow",
            "deny" => "performFirewallDeny",
            "reject" => "performFirewallReject",
            "limit" => "performFirewallLimit",
            "delete" => "performFirewallDelete",
            "insert" => "performFirewallInsert",
            "reset" => "performFirewallReset",
            "default" => "setDefault",
        ) ;
    }

    public function ensurePython() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Python is a dependency for the Firewall Manager, installing", $this->getModuleName()) ;
        $pythonFactory = new Python();
        $pythonModel = $pythonFactory->getModel($this->params) ;
        $pythonModel->ensureInstalled();
    }

}