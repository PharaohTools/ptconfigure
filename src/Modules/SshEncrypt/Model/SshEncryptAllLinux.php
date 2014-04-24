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
            array("method"=> array("object" => $this, "method" => "askForEncryptionKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForTargetPrivateKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "encryptKey", "params" => array()) )
        );
        $this->uninstallCommands = array(
            // array("method"=> array("object" => $this, "method" => "askForRawPrivateKey", "params" => array()) ),
            // array("method"=> array("object" => $this, "method" => "askForRawPublicKey", "params" => array()) ),
            // array("method"=> array("object" => $this, "method" => "askForTargetPrivateKey", "params" => array()) ),
            // array("method"=> array("object" => $this, "method" => "askForTargetPublicKey", "params" => array()) )
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "sshencrypt"; // command and app dir name
        $this->programNameFriendly = "SSH Encrypt!"; // 12 chars
        $this->programNameInstaller = "Encrypt an SSH Private key into a project";
        $this->initialize();
    }

    // @todo this isnt finished below
    public function askForRawPrivateKey() {
        if (isset($this->params["raw-private-key-data"])  ) {
            $this->rawPrivKey = $this->params["raw-private-key-data"] ; }
        else if (isset($this->params["raw-private-key-path"])  ) {
            $this->rawPrivKey = file_get_contents($this->params["raw-private-key-path"]) ; }
        else {
            $question = "Enter Private Key Path:";
            $this->rawPrivKey = self::askForInput($question, true); }
    }

    public function askForTargetPrivateKey() {
        if (isset($this->params["target-private-key"])  ) {
            $this->targetPrivateKey = $this->params["target-private-key"] ;
            return ; }
        if (isset($this->params["guess"]) && isset($this->params["raw-private-key-path"])) {
                //@todo a check if the file doesn't exist
                $dirToStrip = dirname($this->params["raw-private-key-path"]) ;
                $filename = str_replace($dirToStrip, "", $this->params["raw-private-key-path"]) ;
                $encDir = "build/config/cleopatra/SSH/keys/private/encrypted/" ;
                mkdir($encDir, 0777, true) ;
                $this->targetPrivateKey = $encDir.$filename ;
                return ; }
        if (!isset($this->params["target-private-key"]) && !isset($this->params["guess"])) {
            $question = "Enter Target Encrypted Private Key Path:";
            $this->targetPrivateKey = self::askForInput($question, true); }
    }

    public function encryptKey() {
        $keyData = file_get_contents($this->targetPrivateKey) ;
        $encryptionFactory = new \Model\Encryption();
        $encryption = $encryptionFactory->getModel($this->params) ;
        $encryption->encrypt($keyData, $this->params["target-private-key"]) ;
    }

}