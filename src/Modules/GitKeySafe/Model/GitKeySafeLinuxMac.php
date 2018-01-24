<?php

Namespace Model;

class GitKeySafeLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "GitKeySafe";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "addGitKeySafeScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "chmodGitKeySafeScript", "params" => array())),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "delGitKeySafeScript", "params" => array())),
        );
        $this->programDataFolder = "/opt/GitKeySafe"; // command and app dir name
        $this->programNameMachine = "gitkeysafe"; // command and app dir name
        $this->programNameFriendly = "Git Key-Safe Server!"; // 12 chars
        $this->programNameInstaller = "Git Key-Safe Server";
//        $this->statusCommand =  $this->checkGitKeySafeStatus() ; // command git-key-safe"
        $this->versionInstalledCommand = "echo 1.0" ;
        $this->versionRecommendedCommand = "echo 1.0" ;
        $this->versionLatestCommand = "echo 1.0" ;
        $this->initialize();
    }

    public function askStatus() {
        if (file_exists("/usr/bin/git-key-safe")) {
            return true; }
        return false ;
    }

    public function addGitKeySafeScript() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $templateSource = $templatesDir.'/git-key-safe.sh';
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);

        if (PHP_OS === 'Darwin') {
            $newFileName = "/usr/local/bin/git-key-safe" ;
        } else {
            $newFileName = "/usr/bin/git-key-safe" ;
        }

        $logging->log("About to add Git Key-Safe script $newFileName", $this->getModuleName()) ;
        $res =  $templator->template(
            file_get_contents($templateSource),
            array(),
            $newFileName );
        if ($res==true) {
            $logging->log("Git Key-Safe script $newFileName added",
                $this->getModuleName()) ; }
        else {
            $logging->log("Git Key-Safe script $newFileName was not added",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE) ; }
        return $res ;
    }

    public function chmodGitKeySafeScript() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $newFileName = "/usr/bin/git-key-safe" ;
        $cmd = SUDOPREFIX."chmod 775 $newFileName" ;
        $rc = $this->executeAndGetReturnCode($cmd, true, true) ;
        if ($rc["rc"]==0) {
            $logging->log("Git Key-Safe script $newFileName permissions changed to 775",
                $this->getModuleName()) ; }
        else {
            $logging->log("Git Key-Safe script $newFileName permissions failed to change to 775",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE) ; }
        return ($rc["rc"]==0) ? true : false ;
    }

    public function delGitKeySafeScript() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $res = unlink("/usr/bin/git-key-safe");
        if ($res==true) {
            $logging->log("Git Key-Safe Init script config file /usr/bin/git-key-safe removed",
                $this->getModuleName()) ; }
        else {
            $logging->log("Git Key-Safe Init script config file /usr/bin/git-key-safe not removed",
                $this->getModuleName(),
                LOG_FAILURE_EXIT_CODE) ; }
        return $res ;
    }

}