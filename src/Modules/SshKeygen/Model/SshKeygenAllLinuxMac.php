<?php

Namespace Model;

class SshKeygenAllLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $keygenBits;
    private $keygenType;
    private $keygenPath;
    private $keygenComment;
    private $keygenPhrase;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshKeygen";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForKeygenBits", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenType", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenPath", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenComment", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenPassphrase", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "createDirectoryStructure", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doKeyGen", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForKeygenPath", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "removeKey", "params" => array()) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "sshkeygen"; // command and app dir name
        $this->programNameFriendly = "sshkeygen!"; // 12 chars
        $this->programNameInstaller = "SSH Key Generation";
        $this->initialize();
    }

    public function askForKeygenBits() {
        if (isset($this->params["bits"]) ) {
            $this->keygenBits = $this->params["bits"] ; }
        else {
            // @todo check that it actually is a multiple of 1024
            $question = "Enter number of bits for SSH Key (multiple of 1024):";
            $this->keygenBits = self::askForInput($question, true); }
    }

    public function askForKeygenType() {
        if (isset($this->params["type"]) ) {
            $this->keygenType = $this->params["type"] ; }
        else {
            $question = "Choose Key type (rsa/dsa)";
            $this->keygenType = self::askForArrayOption($question, array("rsa", "dsa"),  true); }
    }

    public function askForKeygenPath() {
        if (isset($this->params["path"]) ) {
            $this->keygenPath = $this->params["path"] ; }
        else {
            $question = "Enter path to store private key (public key will be same with .pub):";
            $this->keygenPath = self::askForInput($question, true) ; }
        if (substr($this->keygenPath, 0, 1) != '/') { // relative, so make it full path
            $this->keygenPath = getcwd().'/'.$this->keygenPath ; }
    }

    public function askForKeygenComment() {
        if (isset($this->params["comment"]) ) {
            $this->keygenComment = $this->params["comment"] ; }
        else {
            $question = "Plain text comment appended to public key. None is fine";
            $keygenComment = self::askForInput($question);
            $this->keygenComment = (isset($keygenComment) && strlen($keygenComment)>0) ? $keygenComment : "Pharaoh Tools" ; }
    }

    public function askForKeygenPassphrase() {
        if (isset($this->params["passphrase"]) ) {
            $this->keygenPassphrase = $this->params["passphrase"] ; }
        else if (isset($this->params["guess"]) ) {
            $this->keygenPassphrase = "" ; }
        else {
            $question = "Passphrase to be bound to key. None is fine";
            $keygenPhrase = self::askForInput($question);
            $this->keygenPhrase = (isset($keygenPhrase) && strlen($keygenPhrase)>0) ? $keygenPhrase : "pharaohtools" ; }
    }

    public function removeKey() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (file_exists($this->params["path"])) {
            unlink($this->params["path"]) ;
            $logging->log("Removing File at {$this->params["path"]} in SSH Keygen", $this->getModuleName()) ;
            unlink($this->params["path"].".pub") ;
            $logging->log("Removing File at {$this->params["path"]}.pub in SSH Keygen", $this->getModuleName()) ; }
    }

    public function createDirectoryStructure() {
        if (!file_exists(dirname($this->keygenPath))) {
            // @todo check if this works or is writable beforehand
            // @todo just use the mkdir module
            mkdir(dirname($this->keygenPath), 0775, true) ; }
    }

    public function doKeyGen() {
        $cmd  = "ssh-keygen -b ".$this->keygenBits.' ' ;
        $cmd .= '-t '.$this->keygenType.' ' ;
        $cmd .= '-f '.$this->keygenPath.' ' ;
        $cmd .= '-q -N "'.$this->keygenPhrase.'" -C"'.$this->keygenComment.'"' ;
        $this->executeAndOutput($cmd) ;
    }

}