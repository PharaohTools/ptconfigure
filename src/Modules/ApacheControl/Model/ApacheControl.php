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
      $this->startApache();
      return true;
    }

    public function askWhetherToStopApache() {
      if ( !$this->askForApacheCtl("stop") ) { return false; }
      $this->restartApache();
      return true;
    }

    public function askWhetherToRestartApache() {
      if ( !$this->askForApacheCtl("restart") ) { return false; }
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
      $this->startApache();
      return true;
    }

    public function runAutoPilotApacheCtlRestart($autoPilot){
      if ( !isset($autoPilot["apacheCtlRestartExecute"]) ||
        $autoPilot["apacheCtlRestartExecute"] == false ) { return false; }
      $this->restartApache();
      return true;
    }

    public function runAutoPilotApacheCtlStop($autoPilot){
      if ( !isset($autoPilot["apacheCtlStopExecute"]) ||
        $autoPilot["apacheCtlStopExecute"] == false ) { return false; }
      $this->stopApache();
      return true;
    }

    private function askForApacheCtl($type) {
      if (!in_array($type, array("start", "stop", "restart"))) {
        return false; }
      $question = 'Do you want to Start, Restart or Stop Apache?';
      $input = self::askForArrayOption($question, array("apache2", "httpd"), true) ;
      return true ;
    }

    private function askForApacheCommand() {
      $linuxTypeFromConfig = \Model\AppConfig::getAppVariable("linux-type") ;
      if ( in_array($linuxTypeFromConfig, array("debian", "redhat") ) ) {
        $input = ($linuxTypeFromConfig == "debian") ? "apache2" : "httpd" ; }
      else {
        $question = 'What is the apache service name?';
        $input = self::askForArrayOption($question, array("apache2", "httpd"), true) ; }
      return $input ;
    }

    private function enableVHost($vHostEditorAdditionSymLinkDirectory=null){
        $command = 'a2ensite '.$this->url;
        self::executeAndOutput($command, "a2ensite $this->url done");
        $vHostEnabledDir = (isset($vHostEditorAdditionSymLinkDirectory)) ?
            $vHostEditorAdditionSymLinkDirectory : str_replace("sites-available", "sites-enabled", $this->vHostDir );
        $command = 'sudo ln -s '.$this->vHostDir.'/'.$this->url.' '.$vHostEnabledDir.'/'.$this->url;
        return self::executeAndOutput($command, "VHost Enabled/Symlink Created if not done by a2ensite");
    }

    private function disableVHost(){
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'a2dissite '.$vHost;
            self::executeAndOutput($command, "a2dissite $vHost done");
            $command = 'sudo rm -f '.$this->vHostEnabledDir.'/'.$vHost;
            self::executeAndOutput($command, "VHost $vHost Disabled  if existed"); }
        return true;
    }

    private function restartApache(){
        echo "Restarting Apache...\n";
        $command = "sudo service $this->apacheCommand restart";
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