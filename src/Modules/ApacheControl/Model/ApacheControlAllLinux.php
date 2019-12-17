<?php

Namespace Model;

class ApacheControlAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
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
        return $this->apacheCommand('start');
    }

    public function askWhetherToStopApache() {
        if ( !$this->askForApacheCtl("stop") ) { return false; }
        $this->apacheCommand = $this->askForApacheCommand();
        return $this->apacheCommand('stop');
    }

    public function askWhetherToRestartApache() {
        if ( !$this->askForApacheCtl("restart") ) { return false; }
        $this->apacheCommand = $this->askForApacheCommand();
        return $this->apacheCommand('restart');
    }

    public function askWhetherToReloadApache() {
        if ( !$this->askForApacheCtl("reload") ) { return false; }
        $this->apacheCommand = $this->askForApacheCommand();
        return $this->apacheCommand('reload');
    }

    private function askForApacheCtl($type) {
        if (!in_array($type, array("start", "stop", "restart", "reload"))) { return false; }
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to '.ucfirst($type).' Apache?';
        return self::askYesOrNo($question);
    }

    private function askForApacheCommand() {
        $appConfigFactory = new \Model\AppSettings();
        $appConfigModel = $appConfigFactory->getModel($this->params, "AppConfig");
        $linuxTypeFromConfig = $appConfigModel::getAppVariable("linux-type") ;
        if (isset($this->params["apache-command"])) {
            return $this->params["apache-command"] ; }
        else if (isset($this->params["guess"])) {
            $isDebian = $this->detectDebianApacheVHostFolderExistence();
            $input = ($isDebian) ? "apache2" : "httpd" ; }
        else if ( in_array($linuxTypeFromConfig, array("debian", "redhat") ) ) {
            $input = ($linuxTypeFromConfig == "debian") ? "apache2" : "httpd" ; }
        else {
            $question = 'What is the apache service name?';
            $input = self::askForArrayOption($question, array("apache2", "httpd"), true) ; }
        return $input ;
    }

    private function detectDebianApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }


    protected function apacheCommand($action) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $options = array('start', 'stop', 'reload', 'restart') ;
        if (in_array($action, $options)) {
            $ucf = ucfirst($action) ;
            $logging->log("{$ucf}ing Apache...", $this->getModuleName()) ;
            $command = "sudo service $this->apacheCommand {$action}";
            $res = self::executeAndGetReturnCode($command, true);
            $bool = ($res['rc'] == 0) ? true : false ;
            if ($bool == true) {
                $logging->log("{$ucf}ing Apache Successful", $this->getModuleName()) ;
            } else {
                $logging->log("{$ucf}ing Apache Failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            }
            return $bool ;
        } else {
            $logging->log("Unknown Apache Control Action Requested", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;
        }
    }

}