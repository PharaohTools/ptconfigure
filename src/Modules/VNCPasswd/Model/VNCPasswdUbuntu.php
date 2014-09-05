<?php

Namespace Model;

class VNCPasswdUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04", "14.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $vncUser ;
    protected $myUser ;
    protected $vncPass ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "VNCPasswd";
        $this->installCommands = $this->getInstallCommands() ;
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "vncserver")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "vncserver")) ),
        ) ;
        $this->programDataFolder = "/var/lib/vnc"; // command and app dir name
        $this->programNameMachine = "vnc"; // command and app dir name
        $this->programNameFriendly = " ! VNCPasswd !"; // 12 chars
        $this->programNameInstaller = "VNCPasswd";
        $this->statusCommand = "command vnc4server" ;
        $this->versionInstalledCommand = "sudo apt-cache policy vnc4server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy vnc4server" ;
        $this->versionLatestCommand = "sudo apt-cache policy vnc4server" ;
        $this->initialize();
    }

    public function askForVNCUserName(){
        if (isset($this->params["vnc-user"])) {
            $this->vncUser = $this->params["vnc-user"]; }
        else if (isset($this->params["guess"])) {
            $this->vncUser = $this->myUser; }
        else {
            $question = "Enter VNC User:";
            $this->vncUser = self::askForInput($question, true); }
    }

    public function askForVNCPass(){
        if (isset($this->params["vnc-pass"])) {
            $this->vncPass = $this->params["vnc-pass"]; }
        else {
            $question = "Enter VNC Pass:";
            $this->vncPass = self::askForInput($question, true); }
    }

    protected function setMyUser() {
        $this->myUser = self::executeAndLoad("whoami") ;
        $this->myUser = str_replace("\n", "", $this->myUser) ;
        $this->myUser = str_replace("\r", "", $this->myUser) ;
    }

    protected function getInstallCommands() {
        $ray = array(
            array("method"=> array("object" => $this, "method" => "setMyUser", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForVNCUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForVNCPass", "params" => array()) ),

        ) ;

        if ($this->myUser == $this->vncUser) {
            // no su
            $ray2 = array("command"=> array(
                "echo {$this->vncPass} >/tmp/vnc-file",
                "echo {$this->vncPass} >>/tmp/vnc-file",
                "vncpasswd </tmp/vnc-file >/tmp/vncpasswd.1 2>/tmp/vncpasswd.2",
                "rm /tmp/vnc-file",
            ) ) ; }

        else {
            // su
            $ray2 = array("command"=> array(
                "sudo su {$this->vncUser}",
                "echo {$this->vncPass} >/tmp/vnc-file",
                "echo {$this->vncPass} >>/tmp/vnc-file",
                "vncpasswd </tmp/vnc-file >/tmp/vncpasswd.1 2>/tmp/vncpasswd.2",
                "rm /tmp/vnc-file",
            ) ) ; }
        array_push($ray, $ray2) ;

        return $ray ;
    }

}