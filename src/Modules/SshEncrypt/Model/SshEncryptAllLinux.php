<?php

Namespace Model;

class SshEncryptAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $rawPrivKey;
    private $rawPublicKey;
    private $targetPrivateKey;
    private $targetPublicKey;
    private $dbHost;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshEncrypt";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForRawPrivateKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForRawPublicKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForTargetPrivateKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForTargetPublicKey", "params" => array()) )
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForRawPrivateKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForRawPublicKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForTargetPrivateKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForTargetPublicKey", "params" => array()) )
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "sshencrypt"; // command and app dir name
        $this->programNameFriendly = "SSH Encrypt!"; // 12 chars
        $this->programNameInstaller = "Encrypt an SSH Key Pair within a project";
        $this->initialize();
    }

    public function askForRawPrivateKey($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlNewAdminUser"} ) {
            $this->rawPrivKey = $autoPilot->{$this->autopilotDefiner."MysqlNewAdminUser"}; }
        else {
            $question = "Enter MySQL New Admin User:";
            $this->rawPrivKey = self::askForInput($question, true); }
    }

    public function askForRawPublicKey($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlNewAdminPass"} ) {
            $this->rawPublicKey = $autoPilot->{$this->autopilotDefiner."MysqlNewAdminPass"}; }
        else {
            $question = "Enter MySQL New Admin Pass:";
            $this->rawPublicKey = self::askForInput($question, true); }
    }

    public function askForTargetPrivateKey($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlRootUser"} ) {
            $this->targetPrivateKey = $autoPilot->{$this->autopilotDefiner."MysqlRootUser"}; }
        else {
            $question = "Enter MySQL Root User:";
            $this->targetPrivateKey = self::askForInput($question, true); }
    }

    public function askForTargetPublicKey($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlRootPass"} ) {
            $this->targetPublicKey = $autoPilot->{$this->autopilotDefiner."MysqlRootPass"}; }
        else {
            $question = "Enter MySQL Root Pass:";
            $this->targetPublicKey = self::askForInput($question, true); }
    }

}