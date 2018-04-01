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
        $this->programDataFolder = "/opt/ptvgui"; // command and app dir name
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
        $comms = "taskkill /T /F /IM ptvgui.exe" ;
        $this->executeAndOutput($comms) ;

        // delete package
        if (file_exists(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip")) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip") ;
        }

        // download the package
        // $source = "http://41aa6c13130c155b18f6-e732f09b5e2f2287aef1580c786eed68.r92.cf3.rackcdn.com/ptvgui-win32-{$arch_string}.zip" ;
        $source = "https://repositories.internal.pharaohtools.com/index.php?control=BinaryServer&action=serve&item=pharaoh_virtualize_gui_windows_{$arch_string}" ;
        $this->packageDownload($source, BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip") ;
        // $logging->log("Download to: ". BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip") ;

        // delete package
        if (is_dir(PFILESDIR."PTVGUI")) {
            $logging->log("Delete previous App Directory", $this->getModuleName() ) ;
            $this-> delTree(PFILESDIR."PTVGUI") ;
        }

        // Ensure App Directory
        $logging->log("Ensure App Directory", $this->getModuleName() ) ;
        if (!file_exists(PFILESDIR."PTVGUI")) {
            mkdir(PFILESDIR."PTVGUI", null, true) ;
        }

        // unzip the package
        $logging->log("Unzip the packages", $this->getModuleName() ) ;
        $uzc = getenv('SystemDrive')."\\unzip.exe -quo \"".BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip\" -d \"".PFILESDIR."PTVGUI\" " ;
        // $logging->log("UZ: $uzc", $this->getModuleName() ) ;
        $this->executeAndOutput($uzc) ;

        // move to applications dir
        $logging->log("Add to Start Menu", $this->getModuleName() ) ;
        $lib_path = dirname(__DIR__).DS.'Libraries' ;
        $lib_path .= "\\bscripts\\pinnerJS.bat" ;
        $comm  = "\"{$lib_path}\"" ;
        $comm .= " \"".PFILESDIR."PTVGUI\\ptvgui-win32-{$arch_string}\\ptvgui.exe\""  ;
        $comm1 = $comm . " startmenu"  ;
        $comm2 = $comm . " taskbar"  ;
        // $logging->log("UZ: $comm", $this->getModuleName() ) ;
        $this->executeAndOutput($comm1) ;
        $this->executeAndOutput($comm2) ;

        $logging->log("Add log tailing script", $this->getModuleName() ) ;
        $params = $this->params ;
        $params['source'] = dirname(__DIR__).DS.'Files'.DS.'logtail.php' ;
        $params['target'] = getenv('SystemDrive')."\\logtail.php" ;
        $copyFac = new \Model\Copy();
        $copy = $copyFac->getModel($params, 'Default');
        $copy->performCopyPut();

        // delete package
        if (file_exists(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip")) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip") ;
        }

        return true;

    }


    public function doUninstallCommands() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $arch_string = $this->getArchString() ;

        // delete package
        if (file_exists(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip")) {
            $logging->log("Delete previous package file", $this->getModuleName() ) ;
            unlink(BASE_TEMP_DIR."ptvgui-win32-{$arch_string}.zip") ;
        }

        // delete package
        if (is_dir(PFILESDIR."PTVGUI")) {
            $logging->log("Delete previous App Directory", $this->getModuleName() ) ;
            $this-> delTree(PFILESDIR."PTVGUI") ;
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


}
