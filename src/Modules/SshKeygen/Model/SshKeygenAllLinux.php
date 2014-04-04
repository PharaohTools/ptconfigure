<?php

Namespace Model;

class SshKeygenAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
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

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshKeygen";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForKeygenBits", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenType", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenPath", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForKeygenComment", "params" => array()) ),
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
        if (isset($this->params["ssh-keygen-bits"]) ) {
            $this->keygenBits = $this->params["ssh-keygen-bits"] ; }
        else {
            $question = "Enter number of bits for SSH Key (multiple of 1024):";
            $this->keygenBits = self::askForInput($question, true); }
    }

    public function askForKeygenType() {
        if (isset($this->params["ssh-keygen-type"]) ) {
            $this->keygenType = $this->params["ssh-keygen-type"] ; }
        else {
            $question = "Choose Key type (rsa/dsa)";
            $this->keygenType = self::askForArrayOption($question, array("rsa", "dsa"),  true); }
    }

    public function askForKeygenPath() {
        if (isset($this->params["ssh-keygen-path"]) ) {
            $this->keygenPath = $this->params["ssh-keygen-path"] ; }
        else {
            $question = "Enter path to store private key (public key will be same with .pub):";
            $this->keygenPath = self::askForInput($question, true) ; }
        if (substr($this->keygenPath, 0, 1) != '/') { // relative, so make it full path
            $this->keygenPath = getcwd().'/'.$this->keygenPath ; }
    }

    public function askForKeygenComment() {
        if (isset($this->params["ssh-keygen-comment"]) ) {
            $this->keygenComment = $this->params["ssh-keygen-comment"] ; }
        else {
            $question = "Plain text comment appended to public key. None is fine";
            $keygenComment = self::askForInput($question);
            $this->keygenComment = (isset($keygenComment)) ? $keygenComment : "Pharoah Tools" ; }
    }

    public function removeKey() {
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if (file_exists($this->params["ssh-keygen-path"])) {
            unlink($this->params["ssh-keygen-path"]) ;
            $console->log("Removing File at {$this->params["ssh-keygen-path"]} in SSH Keygen") ;
            unlink($this->params["ssh-keygen-path"].".pub") ;
            $console->log("Removing File at {$this->params["ssh-keygen-path"]}.pub in SSH Keygen") ; }
    }

    public function createDirectoryStructure() {
        if (!file_exists(dirname($this->keygenPath))) {
            mkdir(dirname($this->keygenPath), 0775, true) ; }
    }

    public function doKeyGen() {
        $cmd  = "ssh-keygen -b ".$this->keygenBits.' ' ;
        $cmd .= '-t '.$this->keygenType.' ' ;
        $cmd .= '-f '.$this->keygenPath.' ' ;
        $cmd .= '-q -N "" -C"'.$this->keygenComment.'"' ;
        $this->executeAndOutput($cmd) ;
    }

}