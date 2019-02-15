<?php

Namespace Model;

class PTSourceDesktopGUILinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $sv ;

    // @todo ensure wget is installed
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PTSourceDesktopGUI";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForPTSourceDesktopGUIVersion", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/pharaoh_source_desktop_gui"; // command and app dir name
        $this->programNameMachine = "pharaoh_source_desktop_gui"; // command and app dir name
        $this->programNameFriendly = "Pharaoh Source Desktop GUI"; // 12 chars
        $this->programNameInstaller = "Pharaoh Source Desktop GUI";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "pharaoh_source_desktop_gui";
        $this->programExecutorCommand = '/opt/pharaoh_virtualize_gui/Pharaoh_Virtualize_GUI';
        $this->statusCommand = "cat /usr/bin/ptsgui > /dev/null 2>&1";
        // @todo dont hardcode the installed version
        $this->versionInstalledCommand = 'echo "2.44.0"' ;
        $this->versionRecommendedCommand = 'echo "2.44.0"' ;
        $this->versionLatestCommand = 'echo "2.44.0"' ;
        $this->initialize();
    }

    public function executeDependencies() {
        if (isset($this->params["no-dependencies"])) {
            return;
        }
        $tempVersion = isset($this->params["version"]) ? $this->params["version"] : null ;
        unset($this->params["version"]) ;
        $gitToolsFactory = new \Model\GitTools($this->params);
        $gitTools = $gitToolsFactory->getModel($this->params);
        $gitTools->ensureInstalled();
        $javaFactory = new \Model\Java();
        $java = $javaFactory->getModel($this->params);
        $java->ensureInstalled();
        $this->params["version"] = $tempVersion ;
    }



    public function doInstallCommands() {
        # curl -X POST -O -J -d "control=BinaryServer&action=serve&item=pharaoh_virtualize_gui_linux_x64" https://repositories.internal.pharaohtools.com/index.php
        # https://repositories.internal.pharaohtools.com/index.php?control=BinaryServer&action=serve&item=pharaoh_virtualize_gui_linux_x64
        #
        #
        # get application dir, default /opt/pharaoh_virtualize_gui
        # download the gui zip files to temp dir
        # extract the gui zip file to application dir
        # set file permissions in application dir
        # create the launcher in /usr/share/applications
        # set launcher permissions

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $logging->log("Performing Download of Archive", $this->getModuleName()) ;
        $zip_file_path = '/opt/Pharaoh_Source_Desktop_GUI.zip' ;
        $app_dir = '/opt/pharaoh_source_desktop_gui/' ;
        $downloadFactory = new \Model\Download();
        $params['source'] = 'https://repositories.internal.pharaohtools.com/index.php?control=BinaryServer&action=serve&item=pharaoh_source_desktop_gui_linux_x64' ;
        $params['target'] = $zip_file_path ;
        $download = $downloadFactory->getModel($params);
        $download->performDownload() ;

        $logging->log("Extracting Zip Archive", $this->getModuleName()) ;
        $zip = new \ZipArchive;
        if ($zip->open($zip_file_path) === TRUE) {
            $zip->extractTo($app_dir);
            $zip->close();
        } else {
            return false ;
        }

        $logging->log("Changing readable permissions", $this->getModuleName()) ;
        $chmodFactory = new \Model\Chmod();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['recursive'] = 'true' ;
        $params['executable'] = 'true' ;
        $params['path'] = $app_dir ;
        $params['mode'] = '0755' ;
        $chmod = $chmodFactory->getModel($params) ;
        $chmod->performChmod() ;

        $logging->log("Changing log directory writable permissions", $this->getModuleName()) ;
        $chmodFactory = new \Model\Chmod();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['recursive'] = 'true' ;
        $params['executable'] = 'true' ;
        $params['path'] = $app_dir.'temp_logs' ;
        $params['mode'] = '0777' ;
        $chmod = $chmodFactory->getModel($params) ;
        $chmod->performChmod() ;

        $logging->log("Creating Launcher", $this->getModuleName()) ;
        $templatingFactory = new \Model\Templating();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $replacements = [] ;
        $source_path = dirname(__DIR__).DS.'Templates'.DS.'Pharaoh_Source_Desktop_GUI.desktop' ;
        $target_location = '/usr/share/applications/Pharaoh_Source_Desktop_GUI.desktop' ;
        $templating = $templatingFactory->getModel($params) ;
        $templating->template($source_path, $replacements, $target_location) ;

        $logging->log("Changing Launcher executable permissions", $this->getModuleName()) ;
        $chmodFactory = new \Model\Chmod();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['executable'] = 'true' ;
        $params['path'] = '/usr/share/applications/Pharaoh_Source_Desktop_GUI.desktop' ;
        $params['mode'] = '0755' ;
        $chmod = $chmodFactory->getModel($params) ;
        $chmod->performChmod() ;

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