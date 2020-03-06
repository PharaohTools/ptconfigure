<?php

Namespace Model;

class BaseWindowsApp extends BaseLinuxApp {

    public $defaultStatusCommandPrefix = "where.exe";
    public $exeInstallFlags = '' ;
    public function __construct($params) {
        parent::__construct($params);
    }

    //@todo maybe this should be a helper
    public function packageAdd($packager, $package, $version = null, $versionOperator = "+") {
        # var_dump('BWA packageAdd 1') ;
        $packageFactory = new PackageManager();
        # var_dump('BWA packageAdd 2') ;
        $packageManager = $packageFactory->getModel($this->params) ;
        # var_dump('BWA packageAdd 3') ;
        $packageManager->performPackageEnsure($packager, $package, $this, $version, $versionOperator);
        # var_dump('BWA packageAdd 4') ;
    }

    //@todo maybe this should be a helper
    public function packageRemove($packager, $package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageRemove($packager, $package, $this);
    }

    protected function changePermissions($autoPilot, $target=null){
        # var_dump('BWA: changePermissions') ;
        if ($target != null) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("BUG: may need to find a way to change windows perms", $this->getModuleName() ) ;
        }
    }

    protected function deleteExecutorIfExists(){
        # var_dump('BWA: deleteExecutorIfExists') ;
        $command = 'DEL /S /Q '.$this->programExecutorFolder.DS.$this->programNameMachine;
        self::executeAndOutput($command, "Program Executor Deleted if existed");
        return true;
    }

    public function packageDownload($remote_source, $temp_exe_file=null) {
//        var_dump('BWA packageDownload 1') ;
        if (is_null($temp_exe_file)) {
            $temp_exe_file = $_ENV['TEMP'].DS.'temp.exe' ;
            $this->params['overwrite'] = true ;
        }
//        var_dump('BWA packageDownload 2', $temp_exe_file) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Downloading From {$remote_source}", $this->getModuleName() ) ;
        $logging->log("Downloading To {$temp_exe_file}", $this->getModuleName() ) ;
        if (file_exists($temp_exe_file)) {
            if (isset($this->params['skip-existing'])) {
                $logging->log("File {$temp_exe_file} exists and skip-existing parameter is set, skipping this download.", $this->getModuleName() ) ;
                return true ;
            } elseif (isset($this->params['overwrite'])) {
                $logging->log("File {$temp_exe_file} exists and overwrite parameter is set, removing.", $this->getModuleName() ) ;
                unlink($temp_exe_file) ;
            } else {
                $logging->log("File {$temp_exe_file} exists and overwrite parameter is unset.", $this->getModuleName(), LOG_FAILURE_EXIT_CODE ) ;
                return false ;
            }
        }
        echo "Download Starting ...".PHP_EOL;
        ob_start();
        ob_flush();
        flush();

        $fp = fopen ($temp_exe_file, 'w') ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_source);
        // curl_setopt($ch, CURLOPT_BUFFERSIZE,128);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));
        curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $downloaded = curl_exec($ch);
        fwrite($fp, $downloaded) ;
        curl_close($ch);

        ob_flush();
        flush();

        echo "Done".PHP_EOL ;
        return $temp_exe_file ;
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        $is_quiet = (isset($this->params['quiet']) && ($this->params['quiet'] == true) ) ;
        if ($is_quiet == false) {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl, 2) ;
                if (isset($this->params['percentage'])) {
                    if ($perc % $this->params['percentage'] == 0) {
                        # var_dump('perc', $perc) ;
                        echo "{$perc} % \r" ;
                    }
                } else {
                    # var_dump('perc', $perc) ;
                    echo "{$perc} % \r" ;
                }
            }
            ob_flush();
            flush();
        }
    }

    public function askForVersion($ao=array()){
//        var_dump('vbw 1', $ao);
        $ao = $this->getVersionsAvailable() ;
        if ($ao == false) { $ao = array() ; }
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->params['version'] = $this->params["version"] ; }
        else if (isset($this->params["guess"]) && count($ao)>0 ) {
            $index = count($ao)-1 ;
            $this->params['version'] = $ao[$index] ;
            # var_dump('vbw 2', $this->params['version']);
        }
        else {
            # var_dump('vbw 3');
            $question = 'Enter '.$this->programNameInstaller.' Version';
            $this->params['version'] = self::askForArrayOption($question, $ao, true); }
    }

}