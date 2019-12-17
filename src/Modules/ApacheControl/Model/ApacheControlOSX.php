<?php

Namespace Model;

class ApacheControlOSX extends Base {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $apacheCommand;

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

    public function askWhetherToReloadApache() {
        if ( !$this->askForApacheCtl("reload") ) { return false; }
        $this->apacheCommand = $this->askForApacheCommand();
        $this->reloadApache();
        return true;
    }

    private function askForApacheCtl($type) {
        if (!in_array($type, array("start", "stop", "restart", "reload"))) { return false; }
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to '.ucfirst($type).' Apache?';
        return self::askYesOrNo($question);
    }

    private function askForApacheCommand() {
        if (isset($this->params["apache-command"])) {
            return $this->params["apache-command"] ; }
        else if (isset($this->params["guess"])) {
            $input = "httpd" ; }
        else {
            $question = 'What is the apache service name?';
            $input = self::askForInput($question, true) ; }
        return $input ;
    }

    private function restartApache(){
        echo "Restarting Apache...\n";
        $command = "sudo $this->apacheCommand -k restart";
        return self::executeAndOutput($command);
    }

    private function reloadApache(){
        echo "Reloading Apache Configuration...\n";
        $command = "sudo $this->apacheCommand -k reload";
        return self::executeAndOutput($command);
    }

    private function startApache(){
        echo "Starting Apache...\n";
        $command = "sudo $this->apacheCommand -k start";
        return self::executeAndOutput($command);
    }


    private function stopApache(){
        echo "Stopping Apache...\n";
        $command = "sudo $this->apacheCommand -k stop";
        return self::executeAndOutput($command);
    }

}