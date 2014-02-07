<?php

Namespace Model;

class LighttpdControlLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToCtlLighttpd($ctl) {
      if ( !$this->askForLighttpdCtl($ctl) ) { return false; }
      $this->{$ctl."Lighttpd"}();
      return true;
    }

    public function runAutoPilot($autoPilot) {
        $this->runAutoPilotLighttpdCtlStart($autoPilot);
        $this->runAutoPilotLighttpdCtlRestart($autoPilot);
        $this->runAutoPilotLighttpdCtlStop($autoPilot);
        return true;
    }

    public function runAutoPilotLighttpdCtlStart($autoPilot){
      if ( !isset($autoPilot["lighttpdCtlStartExecute"]) ||
        $autoPilot["lighttpdCtlStartExecute"] == false ) { return false; }
      $this->startLighttpd();
      return true;
    }

    public function runAutoPilotLighttpdCtlRestart($autoPilot){
      if ( !isset($autoPilot["lighttpdCtlRestartExecute"]) ||
        $autoPilot["lighttpdCtlRestartExecute"] == false ) { return false; }
      $this->restartLighttpd();
      return true;
    }

    public function runAutoPilotLighttpdCtlStop($autoPilot){
      if ( !isset($autoPilot["lighttpdCtlStopExecute"]) ||
        $autoPilot["lighttpdCtlStopExecute"] == false ) { return false; }
      $this->stopLighttpd();
      return true;
    }

    private function askForLighttpdCtl($type) {
      if (!in_array($type, array("start", "stop", "restart"))) { return false; }
      if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
      $question = 'Do you want to '.ucfirst($type).' Lighttpd?';
      return self::askYesOrNo($question);
    }

    private function restartLighttpd(){
        echo "Restarting Lighttpd...\n";
        $command = "sudo service lighttpd restart";
        return self::executeAndOutput($command);
    }

    private function startLighttpd(){
        echo "Starting Lighttpd...\n";
        $command = "sudo service lighttpd start";
        return self::executeAndOutput($command);
    }


    private function stopLighttpd(){
        echo "Stopping Lighttpd...\n";
        $command = "sudo service lighttpd stop";
        return self::executeAndOutput($command);
    }

}