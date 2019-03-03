<?php

Namespace Model;

class PTVGUILinux extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PTVGUI";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForPTVGUIVersion", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/pharaoh_virtualize_gui/"; // command and app dir name
        $this->programNameMachine = "ptvgui"; // command and app dir name
        $this->programNameFriendly = "PTV GUI"; // 12 chars
        $this->programNameInstaller = "Pharaoh Vitualize GUI";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "ptvgui";
        $this->programExecutorCommand = '/opt/pharaoh_virtualize_gui/Pharaoh_Virtualize_GUI';
        $this->statusCommand = "cat /usr/bin/ptvgui > /dev/null 2>&1";
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
        $zip_file_path = '/opt/Pharaoh_Virtualize_GUI.zip' ;
        $app_dir = '/opt/pharaoh_virtualize_gui/' ;
        $downloadFactory = new \Model\Download();
        $params['source'] = 'https://repositories.internal.pharaohtools.com/index.php?control=BinaryServer&action=serve&item=pharaoh_virtualize_gui_linux_x64' ;
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

        $logging->log("Remove Zip Archive", $this->getModuleName()) ;
        unlink($zip_file_path) ;

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

        $logging->log("Ensuring log directory existence", $this->getModuleName()) ;
        $mkdirFactory = new \Model\Mkdir();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['recursive'] = 'true' ;
        $params['path'] = $this->programDataFolder.'temp_logs' ;
        $mkdir = $mkdirFactory->getModel($params) ;
        $mkdir->performMkdir() ;

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
        $source_path = dirname(__DIR__).DS.'Templates'.DS.'Pharaoh_Virtualize_GUI.desktop' ;
        $target_location = '/usr/share/applications/Pharaoh_Virtualize_GUI.desktop' ;
        $templating = $templatingFactory->getModel($params) ;
        $templating->template($source_path, $replacements, $target_location) ;

        $logging->log("Changing Launcher executable permissions", $this->getModuleName()) ;
        $chmodFactory = new \Model\Chmod();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['executable'] = 'true' ;
        $params['path'] = '/usr/share/applications/Pharaoh_Virtualize_GUI.desktop' ;
        $params['mode'] = '0755' ;
        $chmod = $chmodFactory->getModel($params) ;
        $chmod->performChmod() ;

        $logging->log("Updating Settings file for Desktop Application", $this->getModuleName()) ;
        $this->settingsFileUpdatePHPDesktop() ;

        $logging->log("Updating Settings file for ISO PHP", $this->getModuleName()) ;
        $this->settingsFileUpdateISOPHP() ;

        $logging->log("Updating Settings file for ISO PHP writable permissions", $this->getModuleName()) ;
        $settings_file_path = $this->programDataFolder.'www/app/Settings/Data/app-settings.json' ;
        $chmodFactory = new \Model\Chmod();
        $params['yes'] = 'true' ;
        $params['guess'] = 'true' ;
        $params['path'] = $settings_file_path ;
        $params['mode'] = '0777' ;
        $chmod = $chmodFactory->getModel($params) ;
        $chmod->performChmod() ;

    }

    public function settingsFileUpdatePHPDesktop() {
        $settings_file_path = $this->programDataFolder.'settings.json' ;
        $settings_string = file_get_contents($settings_file_path) ;
        $settings = json_decode($settings_string, true) ;
//        $new_resolution = $this->resolutionFind() ;
        $settings["chrome"]["log_file"] = "debug.log" ;
//        $settings["main_window"]["default_size"] = $new_resolution ;
        $settings["main_window"]["context_menu"] = true ;
        $settings["main_window"]["enable_menu"] = true ;
        $settings["main_window"]["navigation"] = true ;
        $settings["main_window"]["print"] = true ;
        $settings["main_window"]["view_source"] = true ;
        $settings["main_window"]["devtools"] = true ;
        $string = json_encode($settings, JSON_PRETTY_PRINT) ;
        file_put_contents($settings_file_path, $string) ;
    }

    public function settingsFileUpdateISOPHP() {
        $settings_file_path = $this->programDataFolder.'www/app/Settings/Data/app-settings.json' ;
        $settings_string = file_get_contents($settings_file_path) ;
        $settings = json_decode($settings_string, true) ;
        $project_directories = $this->defaultProjectDirectories() ;
        $settings["project_directories"] = $project_directories ;
        $string = json_encode($settings, JSON_PRETTY_PRINT) ;
        file_put_contents($settings_file_path, $string) ;
    }

    public function resolutionFind() {
        $command = "xdpyinfo  | grep -oP 'dimensions:\s+\K\S+'" ;
        $info = shell_exec($command) ;
        $info = trim($info) ;
        $parts = explode('x', $info) ;
//        var_dump('$parts') ;
//        var_dump($parts) ;
        $width = $parts[0] ;
        $height = $parts[1] ;
        $new_resolution = [] ;
        $new_resolution[] = floor($width / 3) ;
        $new_resolution[] = $height * 0.4 ;
        return $new_resolution ;
    }

    public function defaultProjectDirectories() {
//        $dirs[] = $_SERVER['HOME'] ;
//        $dirs[] = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'Documents' ;
        $dirs[] = '/opt' ;
        return $dirs ;
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