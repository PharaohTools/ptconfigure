<?php

Namespace Model;

class ApacheControl extends Base {

    private $vHostTemplate;
    private $docRoot;
    private $url;
    private $vHostIp;
    private $vHostForDeletion;
    private $vHostEnabledDir;
    private $apacheCommand;
    private $vHostDir = '/etc/apache2/sites-available' ; // no trailing slash

    public function askWhetherToStartApache() {
      if ( !$this->askForApacheCtl("start") ) { return false; }
      $this->apacheCommand = $this->askForApacheCommand();
      $this->startApache();
      return true;
    }

    public function askWhetherToStopApache() {
      if ( !$this->askForApacheCtl("stop") ) { return false; }
      $this->apacheCommand = $this->askForApacheCommand();
      $this->stopApache();
      return true;
    }

    public function askWhetherToRestartApache() {
      if ( !$this->askForApacheCtl("restart") ) { return false; }
      $this->apacheCommand = $this->askForApacheCommand();
      $this->restartApache();
      return true;
    }

    public function runAutoPilot($autoPilot) {
        $this->runAutoPilotApacheCtlStart($autoPilot);
        $this->runAutoPilotApacheCtlRestart($autoPilot);
        $this->runAutoPilotApacheCtlStop($autoPilot);
        return true;
    }

    public function runAutoPilotApacheCtlStart($autoPilot){
        if ( !isset($autoPilot["apacheCtlStartExecute"]) ||
            $autoPilot["apacheCtlStartExecute"] == false ) { return false; }
        $this->params["guess"] = true ;
        $this->apacheCommand = $this->askForApacheCommand();
        $this->startApache();
        return true;
    }

    public function runAutoPilotApacheCtlRestart($autoPilot){
      if ( !isset($autoPilot["apacheCtlRestartExecute"]) ||
        $autoPilot["apacheCtlRestartExecute"] == false ) { return false; }
        $this->params["guess"] = true ;
        $this->apacheCommand = $this->askForApacheCommand();
      $this->restartApache();
      return true;
    }

    public function runAutoPilotApacheCtlStop($autoPilot){
      if ( !isset($autoPilot["apacheCtlStopExecute"]) ||
        $autoPilot["apacheCtlStopExecute"] == false ) { return false; }
        $this->params["guess"] = true ;
        $this->apacheCommand = $this->askForApacheCommand();
      $this->stopApache();
      return true;
    }

    private function askForApacheCtl($type) {
      if (!in_array($type, array("start", "stop", "restart"))) {
        return false; }
      if (isset($this->params["yes"]) && $this->params["yes"]==true) {
          return true ; }
      $question = 'Do you want to '.ucfirst($type).' Apache?';
      return self::askYesOrNo($question);
    }

    private function askForApacheCommand() {
      $linuxTypeFromConfig = \Model\AppConfig::getAppVariable("linux-type") ;
      if ( in_array($linuxTypeFromConfig, array("debian", "redhat") ) ) {
          $input = ($linuxTypeFromConfig == "debian") ? "apache2" : "httpd" ; }
      else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
          $isDebian = $this->detectDebianApacheVHostFolderExistence();
          $input = ($isDebian) ? "apache2" : "httpd" ; }
      else {
          $question = 'What is the apache service name?';
          $input = self::askForArrayOption($question, array("apache2", "httpd"), true) ; }
      return $input ;
    }

    private function detectDebianApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }

    private function restartApache(){
        echo "Restarting Apache...\n";
        $command = "sudo service $this->apacheCommand restart";
        return self::executeAndOutput($command);
    }

    private function reloadApache(){
        echo "Reloading Apache Configuration...\n";
        $command = "sudo service $this->apacheCommand reload";
        return self::executeAndOutput($command);
    }

    private function startApache(){
        echo "Starting Apache...\n";
        $command = "sudo service $this->apacheCommand start";
        return self::executeAndOutput($command);
    }


    private function stopApache(){
        echo "Stopping Apache...\n";
        $command = "sudo service $this->apacheCommand stop";
        return self::executeAndOutput($command);
    }

}