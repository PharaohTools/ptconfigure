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
        $this->programExecutorCommand = '/Applications/pharaohinstaller.app' ;
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

//        // delete package
//        $logging->log("Delete previous packages", $this->getModuleName() ) ;
//        $comms = array( SUDOPREFIX." rm -rf /tmp/ptvgui.app.zip",  SUDOPREFIX." rm -rf /tmp/created_app" ) ;
//        $this->executeAsShell($comms) ;
//
//        // download the package
//        $source = 'http://41aa6c13130c155b18f6-e732f09b5e2f2287aef1580c786eed68.r92.cf3.rackcdn.com/ptvgui.app.zip' ;
//        $this->packageDownload($source, '/tmp/ptvgui.app.zip') ;

        // unzip the package
        $logging->log("Unzip the packages", $this->getModuleName() ) ;
        $comms = array( SUDOPREFIX." unzip -quo /tmp/ptvgui.app.zip -d /tmp/created_app" ) ;
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

//        // delete package
//        $comms = array( SUDOPREFIX."rm -rf /tmp/{$slug}" ) ;
//        $this->executeAsShell($comms) ;

    }

    public function packageDownload($remote_source, $temp_exe_file) {
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

        $fp = fopen ($temp_exe_file, 'w') ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_source);
        // curl_setopt($ch, CURLOPT_BUFFERSIZE,128);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));
        curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        # $error = curl_error($ch) ;
        # var_dump('downloaded', $downloaded, $error) ;
        curl_close($ch);

        ob_flush();
        flush();

        echo "Done".PHP_EOL ;
        return $temp_exe_file ;
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        $is_noprogress = (isset($this->params['noprogress']) ) ? true : false ;
        if ($is_noprogress == false) {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl, 2) ;
                # var_dump('perc', $perc) ;
                echo "{$perc} % \r" ;
            }
        } else {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl) ;
                # var_dump('perc', $perc) ;

                if ($perc !== $this->cur_progress) {
                    echo "{$perc} %  \r\n" ;
                    $this->cur_progress = $perc ;
                }

//                $is_five_multiple = (is_int($perc / 5)) ? true : false ;
////                $fm = fmod($perc, 1) ;
////                $is_five_multiple = (is_int($fm)) ? true : false ;
//                if ($is_five_multiple) {
////                    echo "$fm\n" ;
//                }
            }
        }
        ob_flush();
        flush();
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