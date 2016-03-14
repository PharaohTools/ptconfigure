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
        $this->statusCommand = "command git-key-safe" ; // $this->checkGitKeySafeStatus() ; //
        $this->versionInstalledCommand = "echo 1.0" ;
        $this->versionRecommendedCommand = "echo 1.0" ;
        $this->versionLatestCommand = "echo 1.0" ;
        $this->initialize();
    }

    protected function checkGitKeySafeStatus() {
        if (file_exists("/usr/bin/git-key-safe")) {
            return "exit 0"; }
        return "exit 1" ;
    }

    public function addGitKeySafeScript() {
        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $templateSource = $templatesDir.'/git-key-safe.sh';
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);
        $newFileName = "/usr/bin/git-key-safe" ;
        $templator->template(
            file_get_contents($templateSource),
            array(),
            $newFileName );
        echo "Git Key-Safe script $newFileName added\n";
    }

    public function chmodGitKeySafeScript() {
        $newFileName = "/usr/bin/git-key-safe" ;
        $cmd = SUDOPREFIX."chmod 775 $newFileName" ;
        $this->executeAndOutput($cmd) ;
        echo "Git Key-Safe script $newFileName permissions changed to 775\n";
    }

    public function delGitKeySafeScript() {
        unlink("/usr/bin/git-key-safe");
        echo "Git Key-Safe Init script config file /usr/bin/git-key-safe removed\n";
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 22, 8) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 44, 8) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 44, 8) ;
        return $done ;
    }

}