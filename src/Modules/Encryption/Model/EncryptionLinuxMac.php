<?php

Namespace Model;

class EncryptionLinuxMac extends BaseTemplater {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("any") ;

    //
    protected $unenc ;
    protected $enc ;
    protected $targetLocation ;
    protected $key ;
    protected $perms ;
    protected $owner ;
    protected $group ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Encryption";
        $this->programDataFolder = "";
        $this->programNameMachine = "encryption"; // command and app dir name
        $this->programNameFriendly = "Encryption !"; // 12 chars
        $this->programNameInstaller = "Encryption Functionality";
        $this->initialize();
        $this->setInstallCommands() ;
    }

    protected function setInstallCommands() {

        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "php5-mcrypt"))),
            array("method"=> array("object" => $this, "method" => "askUnEncrypted", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askTarget", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askPerms", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askOwner", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askGroup", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doEncrypt", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "php5-mcrypt"))),
            array("method"=> array("object" => $this, "method" => "askEncrypted", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askTarget", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askKey", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askPerms", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askOwner", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askGroup", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doDecrypt", "params" => array()) ),
        );
    }

    /*
     * @description create an encrypted file from another file or injected data
     * @param $unencrypted a file path or string of data - to encrypt
     * @param $targetLocation a file path string to put the end file. If null, the function will
     *        return the encrypted string
     * @param $key an encryption key to use. If null it'll try to find one from $this->askForEncKey()
     * @param $perms string
     * @param $owner string
     * @param $group string
     *
     * @todo the recursive mkdir should specify perms, owner and group
     * @todo, should we force specify file, incase someone is encrypting a file path. Is that needed?
     */
    public function encrypt($unencrypted, $targetLocation=null, $key=null, $perms="", $owner="", $group="") {
        if (is_file($unencrypted)) {
            if (!file_exists(dirname($targetLocation))) { mkdir(dirname($targetLocation), 0775, true) ; }
            $unencrypted = file_get_contents($unencrypted); }
        $encrypted = $this->getEncrypted($unencrypted, $key) ;
        if (!isset($targetLocation) || strlen($targetLocation)<1) {
            return $encrypted ; }
        $this->saveAndSetPerms($encrypted, $targetLocation, $perms, $owner, $group) ;
    }

    protected function getEncrypted($raw, $key = null) {
        $key = (!is_null($key)) ? $key : $this->key ;
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $raw, MCRYPT_MODE_ECB);
        return $encrypted ;
    }

    public function doEncrypt() {
        $this->encrypt($this->unenc, $this->targetLocation, $this->key, $this->perms, $this->owner, $this->group) ;
    }

    /*
     * @description create an encrypted file from another file or injected data
     * @param $encrypted a file path or string of data - to encrypt
     * @param $targetLocation a file path string to put the end file. If null, the function will
     *        return the decrypted string
     * @param $key an encryption key to use. If null it'll try to find one from $this->askForEncKey()
     *
     * @todo the recursive mkdir should specify perms, owner and group
     * @todo, should we force specify file, incase someone is encrypting a file path. Is that needed?
     */
    public function decrypt($encrypted, $targetLocation=null, $key=null, $perms="", $owner="", $group="") {
        if (is_file($encrypted)) { $encrypted = file_get_contents($encrypted); }
        $decrypted = $this->getDecrypted($encrypted, $key) ;
        if (is_null($targetLocation)) { return $decrypted ; }
        $this->saveAndSetPerms($decrypted, $targetLocation, $perms, $owner, $group) ;
    }

    protected function getDecrypted($encrypted, $key = null) {
        $key = (is_file($key)) ? file_get_contents($key) : $key ;
        $key = (!is_null($key)) ? $key : $this->key ;
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_ECB);
        return $decrypted ;
    }

    public function doDecrypt() {
        $this->decrypt($this->unenc, $this->targetLocation, $this->key, $this->perms, $this->owner, $this->group);
    }

    protected function saveAndSetPerms($fileData, $targetLocation=null, $perms="", $owner="", $group="") {
        if ($perms != "") { exec("sudo chmod $perms $targetLocation"); }
        if ($owner != "") { exec("sudo chown $owner $targetLocation"); }
        if ($group != "") { exec("sudo chgrp $group $targetLocation"); }
        file_put_contents($targetLocation, $fileData) ;
    }

    public function askUnEncrypted() {
        if (isset($this->params["unencrypted-data"]) ) {
            $this->unenc = $this->params["unencrypted-data"] ; }
        else {
            $question = "Enter either a filepath or raw data to encrypt";
            $this->unenc = self::askForInput($question, true); }
    }

    public function askEncrypted() {
        if (isset($this->params["encrypted-data"]) ) {
            $this->enc = $this->params["encrypted-data"] ; }
        else {
            $question = "Enter either a filepath or raw data to decrypt";
            $this->enc = self::askForInput($question, true); }
    }

    public function askTarget() {
        if (isset($this->params["encryption-target-file"]) ) {
            $this->targetLocation = $this->params["encryption-target-file"] ; }
        else {
            $question = "Enter output file path:";
            $this->targetLocation = self::askForInput($question, true); }
    }

    public function askKey() {
        if (isset($this->params["encryption-key"]) ) {
            $this->key = $this->params["encryption-key"] ; }
        else {
            $question = "Enter Encryption Key";
            $this->key = self::askForInput($question, true); }
    }

    public function askPerms() {
        if (isset($this->params["encryption-file-permissions"]) ) {
            $this->perms = $this->params["encryption-file-permissions"] ; }
        else {
            $question = "Enter permissions for output file (Empty is okay):";
            $this->perms = self::askForInput($question); }
    }

    public function askOwner() {
        if (isset($this->params["encryption-file-owner"]) ) {
            $this->owner = $this->params["encryption-file-owner"] ; }
        else {
            $question = "Enter Owner for output file (Empty is okay):";
            $this->owner = self::askForInput($question); }
    }

    public function askGroup() {
        if (isset($this->params["encryption-file-group"]) ) {
            $this->group = $this->params["encryption-file-group"] ; }
        else {
            $question = "Enter Group for output file (Empty is okay):";
            $this->group = self::askForInput($question); }
    }

}