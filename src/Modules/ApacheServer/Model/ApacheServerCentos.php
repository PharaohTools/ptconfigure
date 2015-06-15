<?php

Namespace Model;

class ApacheServerCentos extends BaseLinuxApp {

  // Compatibility
  public $os = array("Linux") ;
  public $linuxType = array("Redhat") ;
  public $distros = array("any") ;
  public $versions = array( array("5.9", "+")) ;
  public $architectures = array("any") ;

  // Model Group
  public $modelGroup = array("Default") ;

  public function __construct($params) {
      parent::__construct($params);
      $this->autopilotDefiner = "ApacheServer";
      $this->installCommands = array(
          array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "httpd")) ),
          array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
      $this->uninstallCommands = array(
          array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "httpd")) ),
          array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
      $this->programDataFolder = "/opt/ApacheServer"; // command and app dir name
      $this->programNameMachine = "apacheserver"; // command and app dir name
      $this->programNameFriendly = "Apache Server!"; // 12 chars
      $this->programNameInstaller = "Apache Server";
      $this->statusCommand = "httpd -v" ;
      $this->versionInstalledCommand = "sudo yum info httpd" ;
      $this->versionRecommendedCommand = "sudo yum info httpd" ;
      $this->versionLatestCommand = "sudo yum info httpd" ;
      $this->initialize();
  }

    public function apacheRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("httpd");
        $serviceManager->restart();
    }

    public function versionInstalledCommandTrimmer($text) {
        $lines = explode("\n", $text) ;
        foreach ($lines as $line) {
            if (substr($line, 7)=="Version") {
                $colon = strpos($line, ":");
                $version = substr($line, $colon+2, strlen($line)-1) ;
                return $version ; } }
        return null ;
    }
//
//    public function versionLatestCommandTrimmer($text) {
//        if (strpos($text, "Installed: (none)") !== false) { $rest = substr($text, 42) ; }
//        else {  $rest = substr($text, 52) ; }
//        $spacepos = strpos($rest, "\n") ;
//        $done =  substr($rest, 0, $spacepos) ;
//        return $done ;
//    }
//
//    public function versionRecommendedCommandTrimmer($text) {
//        $done = substr($text, 53, 17) ;
//        return $done ;
//    }

}