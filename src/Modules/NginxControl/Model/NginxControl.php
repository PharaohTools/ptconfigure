<?php

Namespace Model;

class NginxControl extends Base {

    private $vHostTemplate;
    private $docRoot;
    private $url;
    private $vHostIp;
    private $vHostForDeletion;
    private $vHostEnabledDir;
    private $vHostDir = '/etc/nginx2/sites-available' ; // no trailing slash

    public function askWhetherToStartNginx() {
      if ( !$this->askForNginxCtl("start") ) { return false; }
      $this->startNginx();
      return true;
    }

    public function askWhetherToStopNginx() {
      if ( !$this->askForNginxCtl("stop") ) { return false; }
      $this->stopNginx();
      return true;
    }

    public function askWhetherToRestartNginx() {
      if ( !$this->askForNginxCtl("restart") ) { return false; }
      $this->restartNginx();
      return true;
    }

    public function runAutoPilot($autoPilot) {
        $this->runAutoPilotNginxCtlStart($autoPilot);
        $this->runAutoPilotNginxCtlRestart($autoPilot);
        $this->runAutoPilotNginxCtlStop($autoPilot);
        return true;
    }

    public function runAutoPilotNginxCtlStart($autoPilot){
      if ( !isset($autoPilot["nginxCtlStartExecute"]) ||
        $autoPilot["nginxCtlStartExecute"] == false ) { return false; }
      $this->startNginx();
      return true;
    }

    public function runAutoPilotNginxCtlRestart($autoPilot){
      if ( !isset($autoPilot["nginxCtlRestartExecute"]) ||
        $autoPilot["nginxCtlRestartExecute"] == false ) { return false; }
      $this->restartNginx();
      return true;
    }

    public function runAutoPilotNginxCtlStop($autoPilot){
      if ( !isset($autoPilot["nginxCtlStopExecute"]) ||
        $autoPilot["nginxCtlStopExecute"] == false ) { return false; }
      $this->stopNginx();
      return true;
    }

    private function askForNginxCtl($type) {
      if (!in_array($type, array("start", "stop", "restart"))) {
        return false; }
      if (isset($this->params["yes"]) && $this->params["yes"]==true) {
          return true ;
      }
      $question = 'Do you want to '.ucfirst($type).' Nginx?';
      return self::askYesOrNo($question);
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

    private function restartNginx(){
        echo "Restarting Nginx...\n";
        $command = "sudo service nginx restart";
        return self::executeAndOutput($command);
    }

    private function startNginx(){
        echo "Starting Nginx...\n";
        $command = "sudo service nginx start";
        return self::executeAndOutput($command);
    }


    private function stopNginx(){
        echo "Stopping Nginx...\n";
        $command = "sudo service nginx stop";
        return self::executeAndOutput($command);
    }

}