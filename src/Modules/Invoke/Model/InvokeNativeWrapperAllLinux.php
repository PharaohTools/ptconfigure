<?php

Namespace Model;

class InvokeNativeWrapperAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("NativeWrapper") ;

    public $target ;
    public $privkey ;
    public $pubkey ;
    public $port ;
    public $timeout ;

    public function login($username, $password = '') {
        if (file_exists($password)) {
            $this->privkey = $password  ;
            $connection = ssh2_connect($this->target, $this->port, array('hostkey'=>'ssh-rsa'));
            if ($this->pubkey == null) {
                // @todo we should highlight somewhere that this public key needs to be set because surely it NOT needed?
                $this->pubkey = $this->privkey.".pub" ; }
            if (ssh2_auth_pubkey_file($connection, $username, $this->pubkey, $this->privkey, 'secret')) {
                return true ; } }
        else {
            $connection = ssh2_connect($this->target, $this->port);
            if (ssh2_auth_password($connection, $username, $password)) {
                return true ; } }
        return false ;
    }

    protected function getKeyIfAvailable($pword) {
        if (substr($pword, 0, 1) == '~') {
            $home = $_SERVER['HOME'] ;
            $pword = str_replace('~', $home, $pword) ; }
        if (file_exists($pword)) {
            if (!class_exists('Crypt_RSA')) {
                $srcFolder =  str_replace("/Model", "/Libraries", dirname(__FILE__) ) ;
                $rsaFile = $srcFolder."/seclib/Crypt/RSA.php" ;
                require_once($rsaFile) ; }
            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents($pword));
            return $key ; }
        return $pword ;
    }

    protected function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("PHAROAHPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("PHAROAHPROMPT") ;
        return str_replace("PHAROAHPROMPT", "", $returnVar) ;
    }

}