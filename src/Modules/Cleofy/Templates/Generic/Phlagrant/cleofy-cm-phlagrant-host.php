<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;
    protected $myUser ;
    protected $papyrus ;
    protected $sftpParams ;

    public function __construct() {
        parent::__construct() ;
        $this->loadPapyrusLocal();
        $this->setSftpParams();
        $this->setSteps();
    }

    /* Steps */
    protected function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Phlagrant Host"),),),

                // Copy SSH Private Key
                array ( "Logging" => array( "log" => array( "log-message" => "Lets push over our user SSH Keys" ),),),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => $this->sftpParams["source"] ,
                    "target" => $this->sftpParams["target"],
                    "port" => $this->sftpParams["port"],
                    "timeout" => $this->sftpParams["timeout"],
                    "servers" => $this->sftpParams["servers"],
                    "mkdir" => true
                ),),),

                array ( "Logging" => array( "log" => array(
                    "log-message" => "Cleopatra Configuration Management of your Phlagrant Host complete"
                ),),),

            );

    }

    protected function setSftpParams() {
        $srv = array(
            "user" => $this->papyrus["username"] ,
            "password" => $this->papyrus["password"] ,
            "target" => $this->papyrus["target"] );
        $this->sftpParams["yes"] = true ;
        $this->sftpParams["guess"] = true ;
        $this->sftpParams["servers"] = serialize(array($srv)) ;
        $this->sftpParams["source"] = "/home/{$this->myUser}/.ssh/id_rsa" ;
        $this->sftpParams["target"] = "/home/phlagrant/.ssh/id_rsa" ;
        $this->sftpParams["port"] = (isset($this->papyrus["port"])) ? $this->papyrus["port"] : 22 ;
        $this->sftpParams["timeout"] = (isset($this->papyrus["timeout"])) ? $this->papyrus["timeout"] : 30 ; # your papyrus should have this from extending base class
    }

    // @todo $boxname should read from the phlagrant file, probably. it should definitely not be hardcoded
    protected function loadPapyrusLocal() {
        $boxname = "phlagrant-box" ;
        $fp = getcwd()."/papyrusfilelocal" ;
        $pl = file_get_contents($fp) ;
        $pla = unserialize($pl);
        if (is_array($pla) && count($pla)>0) {
            $this->papyrus = $pla[$boxname] ; }
        else {
            $this->papyrus = array() ; }
    }

}
