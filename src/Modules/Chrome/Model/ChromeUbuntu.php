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
                "wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -",
                "sudo apt-get update -y",
                "sudo sh -c 'echo \"deb http://dl.google.com/linux/chrome/deb/ stable main\" >> /etc/apt/sources.list.d/google.list'"
            ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array(
                "Apt", "google-chrome-stable")
            )),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "google-chrome-stable")) ), );
        $this->programDataFolder = "/opt/Chrome"; // command and app dir name
        $this->programNameMachine = "google-chrome-stable"; // command and app dir name
        $this->programNameFriendly = "GoogleChrome"; // 12 chars
        $this->programNameInstaller = "Chrome";
        $this->statusCommand = "google-chrome-stable -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy google-chrome-stable" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy google-chrome-stable" ;
        $this->versionLatestCommand = "sudo apt-cache policy google-chrome-stable" ;
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

}