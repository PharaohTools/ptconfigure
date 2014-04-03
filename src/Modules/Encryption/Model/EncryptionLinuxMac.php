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

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Encryption";
        $this->installCommands = array();
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "encryption"; // command and app dir name
        $this->programNameFriendly = "Encryption !"; // 12 chars
        $this->programNameInstaller = "Encryption Functionality";
        $this->initialize();
    }

    protected function askForEncKey() {
        if (isset($this->params["ssh-keygen-bits"]) ) {
            return $this->params["ssh-keygen-bits"] ; }
        else if (strlen(getenv("PHAROAH_ENC_KEY"))>0) {
            return $this->params["ssh-keygen-bits"] ; }
        else {
            $question = "Enter Encryption Key:";
            return self::askForInput($question, true); }
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
     */
    public function encrypt($unencrypted, $targetLocation = null, $key = null, $perms = null, $owner = null, $group = null) {
        if (is_file($unencrypted)) {
            if (!file_exists(dirname($targetLocation))) { mkdir(dirname($targetLocation), 0775, true) ; }
            $unencrypted = file_get_contents($unencrypted); }
        $encrypted = $this->getEncrypted($unencrypted, $key) ;
        if (is_null($targetLocation)) {
            return $encrypted ; }
        if ($perms != null) { exec("sudo chmod $perms $targetLocation"); }
        if ($owner != null) { exec("sudo chown $owner $targetLocation"); }
        if ($group != null) { exec("sudo chgrp $group $targetLocation"); }
        file_put_contents($targetLocation, $encrypted) ;
    }

    protected function getEncrypted($raw, $key = null) {
        $key = (!is_null($key)) ? $key : $this->askForEncKey() ;
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $raw, MCRYPT_MODE_ECB);
        return $encrypted ;
    }

    /*
     * @description create an encrypted file from another file or injected data
     * @param $encrypted a file path or string of data - to encrypt
     * @param $targetLocation a file path string to put the end file. If null, the function will
     *        return the decrypted string
     * @param $key an encryption key to use. If null it'll try to find one from $this->askForEncKey()
     *
     * @todo the recursive mkdir should specify perms, owner and group
     */
    public function decrypt($encrypted, $key = null, $targetLocation = null) {
        $fData = (is_file($encrypted)) ? file_get_contents($encrypted) : $encrypted ;
        if (!file_exists(dirname($targetLocation))) { mkdir(dirname($targetLocation), 0775, true) ; }
        file_put_contents($targetLocation, $fData) ;
    }

    protected function getDecrypted($encrypted, $key = null) {
        $key = (!is_null($key)) ? $key : $this->askForEncKey() ;
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_ECB);
        return $decrypted ;
    }

}