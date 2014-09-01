<?php

Namespace Model;

class SFTPNativeWrapperAllLinux extends Base {

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

    protected $connection ;

    public function login($username, $password = '') {
        if (file_exists($password)) {
            $this->privkey = $password  ;
            $connection = ssh2_connect($this->target, $this->port, array('hostkey'=>'ssh-rsa'));
            if ($this->pubkey == null) {
                // @todo we should highlight somewhere that this public key needs to be set because surely it NOT needed?
                $this->pubkey = $this->privkey.".pub" ; }
            if (ssh2_auth_pubkey_file($connection, $username, $this->pubkey, $this->privkey, 'secret')) {
                $this->connection = $connection ;
                return true ; } }
        else {
            $connection = ssh2_connect($this->target, $this->port);
            if (ssh2_auth_password($connection, $username, $password)) {
                $this->connection = $connection ;
                return true ; } }
        return false ;
    }

    public function exec($command) {
        $stream = ssh2_exec($this->connection, $command);
        stream_set_blocking( $stream, true );
        stream_set_timeout ( $stream, 100 );
        $out = stream_get_contents ( $stream );
        // $out = fread($stream,4096);
        fclose($stream);
        return $out ;
    }

    public function mkdir($dn) {
        $sftp = ssh2_sftp($this->connection);
        return ssh2_sftp_mkdir ($sftp, $dn, 0775, true) ;
    }

    protected function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("PHARAOHPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("PHARAOHPROMPT") ;
        return str_replace("PHARAOHPROMPT", "", $returnVar) ;
    }

}