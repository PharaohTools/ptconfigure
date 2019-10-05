<?php

Namespace Model;

class PTVGUIWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows", 'WINNT') ;
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
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "doUninstallCommands", "params" => array()) ),
        );
        $this->programDataFolder = PFILESDIR."PTVGUI\\"; // command and app dir name
        $this->programNameMachine = "ptvgui"; // command and app dir name
        $this->programNameFriendly = "PTV GUI"; // 12 chars
        $this->programNameInstaller = "Pharaoh Vitualize GUI";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "ptvgui";
        $this->programExecutorCommand = 'ptvgui';
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

        $arch_string = $this->getArchString() ;

        // Stop running PTV GUI
        $logging->log("Stop Pharaoh Virtualize GUI if it is running", $this->getModuleName() ) ;
        $comms = "taskkill /T /F /IM Pharaoh_Virtualize_GUI.exe" ;
        $this->executeAndOutput($comms) ;

        // delete package
        $package_file = BASE_TEMP_DIR."Pharaoh_Virtualize_GUI.windows.zip" ;
        if (file_exists($package_file)) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink($package_file) ;
        }

        // download the package
        // $source = "http://41aa6c13130c155b18f6-e732f09b5e2f2287aef1580c786eed68.r92.cf3.rackcdn.com/Pharaoh_Virtualize_GUI.windows.zip" ;
        $source = "https://repositories.internal.pharaohtools.com/index.php?control=BinaryServer&action=serve&item=pharaoh_virtualize_gui_windows_{$arch_string}" ;
        $this->guiDownload($source, $package_file) ;
        // $logging->log("Download to: ". $package_file) ;

        // delete package
        if (is_dir(PFILESDIR."Pharaoh_Virtualize_GUI")) {
            $logging->log("Delete previous App Directory", $this->getModuleName() ) ;
            $this-> delTree(PFILESDIR."Pharaoh_Virtualize_GUI") ;
        }

        // Ensure App Directory
        $logging->log("Ensure App Directory", $this->getModuleName() ) ;
        if (!file_exists(PFILESDIR."Pharaoh_Virtualize_GUI")) {
            mkdir(PFILESDIR."Pharaoh_Virtualize_GUI", null, true) ;
        }

        // unzip the package
        $logging->log("Unzip the packages", $this->getModuleName() ) ;
//        $uzc = getenv('SystemDrive')."\\unzip.exe -quo \"".BASE_TEMP_DIR."Pharaoh_Virtualize_GUI.windows.zip\" -d \"".PFILESDIR."PTVGUI\" " ;
        // $logging->log("UZ: $uzc", $this->getModuleName() ) ;
        $zip = new \ZipArchive;
        if ($zip->open($package_file) === TRUE) {
            $zip->extractTo(PFILESDIR."Pharaoh_Virtualize_GUI");
            $zip->close();
            $logging->log("Unzip Successful", $this->getModuleName() ) ;
        } else {
            $logging->log("Unzip Failed", $this->getModuleName() ) ;
        }
//        $this->executeAndOutput($uzc) ;

        // move to applications dir
        $logging->log("Add to Start Menu", $this->getModuleName() ) ;
        $lib_path = dirname(__DIR__).DS.'Libraries' ;
        $lib_path .= "\\bscripts\\pinnerJS.bat" ;
        $comm  = "\"{$lib_path}\"" ;
        $comm .= " \"".PFILESDIR."Pharaoh_Virtualize_GUI\\Pharaoh_Virtualize_GUI.exe\""  ;
        $comm1 = $comm . " startmenu"  ;
        $comm2 = $comm . " taskbar"  ;
        // $logging->log("UZ: $comm", $this->getModuleName() ) ;
        $this->executeAndOutput($comm1) ;
        $this->executeAndOutput($comm2) ;

        $logging->log("Add log tailing script", $this->getModuleName() ) ;
        $params = $this->params ;
        $params['source'] = dirname(__DIR__).DS.'Files'.DS.'logtail.php' ;
        $params['target'] = PFILESDIR."logtail.php" ;
        $copyFac = new \Model\Copy();
        $copy = $copyFac->getModel($params, 'Default');
        $copy->performCopyPut();

        // delete package
        if (file_exists($package_file)) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink($package_file) ;
        }

        $logging->log("Updating Settings file for Desktop Application", $this->getModuleName()) ;
        $this->settingsFileUpdatePHPDesktop() ;

        $logging->log("Updating Settings file for ISO PHP", $this->getModuleName()) ;
        $this->settingsFileUpdateISOPHP() ;

        return true;

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
        $settings_file_path = $this->programDataFolder.'www'.DS.'app'.DS.'Settings'.DS.'Data'.DS.'app-settings.json' ;
        $settings_string = file_get_contents($settings_file_path) ;
        $settings = json_decode($settings_string, true) ;
        $project_directories = $this->defaultProjectDirectories() ;
        $settings["project_directories"] = $project_directories ;
        $string = json_encode($settings, JSON_PRETTY_PRINT) ;
        file_put_contents($settings_file_path, $string) ;
    }

    public function resolutionFind() {
        $command = "wmic desktopmonitor get screenheight, screenwidth" ;
        $info = shell_exec($command) ;
        $info = trim($info) ;
//        var_dump('finding resolution', $info) ;
        $parts = explode('x', $info) ;
        $width = $parts[0] ;
        $height = $parts[1] ;
        $new_resolution = [] ;
        $new_resolution[] = floor($width / 3) ;
        $new_resolution[] = $height * 0.4 ;
        return $new_resolution ;
    }

    public function defaultProjectDirectories() {
        $dir_options[] = $_SERVER['HOMEDRIVE'].$_SERVER['HOMEPATH'] ;
//        $dir_options[] = $_SERVER['HOMEDRIVE'].$_SERVER['HOMEPATH'].DS.'Documents' ;
        $dirs = [] ;
        foreach ($dir_options as $dir_option) {
            if (is_dir($dir_option)) {
                $dirs[] = $dir_option ;
            }
        }
        return $dirs ;
    }

    public function doUninstallCommands() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        // delete package
        $package_file = BASE_TEMP_DIR."Pharaoh_Virtualize_GUI.windows.zip" ;
        if (file_exists($package_file)) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink($package_file) ;
        }

        // delete package
        if (is_dir(PFILESDIR."Pharaoh_Virtualize_GUI")) {
            $logging->log("Delete previous App Directory", $this->getModuleName() ) ;
            $this-> delTree(PFILESDIR."Pharaoh_Virtualize_GUI") ;
        }

        return true;

    }

    public function getArchString() {
        $sys = new \Model\SystemDetectionAllOS();
        $arch = $sys->architecture ;
        $arch_string = '' ;
        if ($arch == '32') {
            $arch_string = 'ia32' ;
        } else if ($arch == '64') {
            $arch_string = 'x64' ;
        }
        return $arch_string ;
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

    public function delTree($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . DS . $file;
                if ( is_dir($full) ) {
                    $this->delTree($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }


    public function guiDownload($remote_source, $temp_exe_file) {

        if (file_exists($temp_exe_file)) {
            unlink($temp_exe_file) ;
        }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Downloading From {$remote_source}", $this->getModuleName() ) ;

        echo "Download Starting ...".PHP_EOL;
        ob_start();
        ob_flush();
        flush();

        $downloaded = file_get_contents($remote_source) ;
        $fp = fopen ($temp_exe_file, 'w') ;
        fwrite($fp, $downloaded) ;

        ob_flush();
        flush();

        echo "Done".PHP_EOL ;
        return $temp_exe_file ;
    }

}
