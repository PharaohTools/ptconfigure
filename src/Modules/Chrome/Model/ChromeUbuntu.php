<?php

Namespace Model;

class ChromeUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Chrome";
        $this->installCommands = array(
            array("command"=> array(
                "wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -" ) ),
            array("command"=> array(
                SUDOPREFIX."apt-get update -y" ) ),
            array("method"=> array("object" => $this, "method" => "ensureAptSourceExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array( "Apt", "google-chrome-stable") )),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "google-chrome-stable")) ),
            array("method"=> array("object" => $this, "method" => "ensureAptSourceRemoved", "params" => array()) ),
        );
        $this->programDataFolder = "/opt/Chrome"; // command and app dir name
        $this->programNameMachine = "google-chrome-stable"; // command and app dir name
        $this->programNameFriendly = "GoogleChrome"; // 12 chars
        $this->programNameInstaller = "Chrome";
        $this->statusCommand = "which google-chrome-stable" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy google-chrome-stable" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy google-chrome-stable" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy google-chrome-stable" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $candidateStarts = strpos($text, "Installed: ") ;
        $candidateEnds = $candidateStarts + 11 ;
        $theRest = substr($text, $candidateEnds) ;
        $firstSpaceAfterNumber = strpos($theRest, "\n") ;
        $done = substr($text, $candidateEnds, $firstSpaceAfterNumber) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $candidateStarts = strpos($text, "Candidate: ") ;
        $candidateEnds = $candidateStarts + 11 ;
        $theRest = substr($text, $candidateEnds) ;
        $firstSpaceAfterNumber = strpos($theRest, "\n") ;
        $done = substr($text, $candidateEnds, $firstSpaceAfterNumber) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $candidateStarts = strpos($text, "Candidate: ") ;
        $candidateEnds = $candidateStarts + 11 ;
        $theRest = substr($text, $candidateEnds) ;
        $firstSpaceAfterNumber = strpos($theRest, "\n") ;
        $done = substr($text, $candidateEnds, $firstSpaceAfterNumber) ;
        return $done ;
    }

    public function ensureAptSourceExists() {
        $fileFactory = new \Model\File() ;
        $file = $fileFactory->getModel($this->params) ;
        $file->setFile("/etc/apt/sources.list.d/google.list") ;
        $file->create() ;
        $file->setSearchLine("deb http://dl.google.com/linux/chrome/deb/ stable main") ;
        $file->shouldHaveLine();
    }

    public function ensureAptSourceRemoved() {
        $fileFactory = new \Model\File() ;
        $file = $fileFactory->getModel($this->params) ;
        $file->setFile("/etc/apt/sources.list.d/google.list") ;
        $file->delete() ;
    }

}