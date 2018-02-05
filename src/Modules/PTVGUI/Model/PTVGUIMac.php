<?php

Namespace Model;

class PTVGUIMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $sv ;

    protected $cur_progress ;

    // @todo ensure wget is installed
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PTVGUI";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForPTVGUIVersion", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/ptvgui"; // command and app dir name
        $this->programNameMachine = "ptvgui"; // command and app dir name
        $this->programNameFriendly = "PTV GUI"; // 12 chars
        $this->programNameInstaller = "Pharaoh Vitualize GUI";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "ptvgui";
        $this->programExecutorCommand = '/Applications/ptvgui.app' ;
        $this->statusCommand = "cat /usr/bin/ptvgui > /dev/null 2>&1";
        // @todo dont hardcode the installed version
        $this->versionInstalledCommand = 'echo "2.44.0"' ;
        $this->versionRecommendedCommand = 'echo "2.44.0"' ;
        $this->versionLatestCommand = 'echo "2.44.0"' ;
        $this->initialize();
    }

    public function doInstallCommands() {

        $this->params['noprogress'] = true ;

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        // delete package
        $logging->log("Delete previous packages", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX." rm -rf /tmp/ptvgui.app.zip",  SUDOPREFIX." rm -rf /tmp/created_ptvgui_app" ) ;
        $this->executeAsShell($comms) ;

        // download the package
        $source = 'http://41aa6c13130c155b18f6-e732f09b5e2f2287aef1580c786eed68.r92.cf3.rackcdn.com/ptvgui.app.zip' ;
        $this->packageDownload($source, '/tmp/ptvgui.app.zip') ;

        // unzip the package
        $logging->log("Unzip the packages", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX." unzip -quo /tmp/ptvgui.app.zip -d /tmp/created_ptvgui_app" ) ;
//        var_dump($comms) ;
        $this->executeAsShell($comms) ;

        // change mode
        $logging->log("Change Mode", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX." chmod -R 777 /tmp/created_app/ptvgui.app" ) ;
        $this->executeAsShell($comms) ;

        // move to applications dir
        $logging->log("Move to Apps Dir", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX."mv /tmp/created_app/ptvgui.app /Applications/" ) ;
        $this->executeAsShell($comms) ;

        // change file name
        $logging->log("Change File Name", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX."mv /Applications/ptvgui.app /Applications/PTV\ GUI.app" ) ;
        $this->executeAsShell($comms) ;

        // delete package
        $logging->log("Delete packages", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX." rm -rf /tmp/ptvgui.app.zip",  SUDOPREFIX." rm -rf /tmp/created_ptvgui_app" ) ;
        $this->executeAsShell($comms) ;

        return true;

    }

    public function versionInstalledCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

}