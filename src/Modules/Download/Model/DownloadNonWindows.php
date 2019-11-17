<?php

Namespace Model;

class DownloadNonWindows extends BaseLinuxApp {

    // Compatibility
    public $os = array('Linux', 'Darwin') ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToDownload() {
        return $this->performDownload();
    }

    protected function attemptToLoad($sourceDataPath){
        if (file_exists($sourceDataPath)) {
            return file_get_contents($sourceDataPath) ; }
        else {
            return null ; }
    }

    public function performDownload() {
        $sourceDataPath = $this->getSourceFilePath("remote");
        $targetPath = $this->getTargetFilePath("local");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Performing Download...", $this->getModuleName());

        $is_conn = $this->is_connected($sourceDataPath) ;
        if ($is_conn === false) {
            $logging->log("Unable to access network", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false;
        }

        $res = $this->packageDownload($sourceDataPath, $targetPath) ;
        if ($res == true) {
            $logging->log("Download Completed Successfully...", $this->getModuleName());
            return true;
        }
        $logging->log("Download Failed...", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
        return false;
    }

    protected function is_connected($addr) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
//        var_dump( stripos($addr, 'https') ) ;
        if (stripos($addr, 'https') === 0) {
            $parse = parse_url($addr);
            $port = 443 ;
            $addr = str_replace($parse['host'], $parse['host'].':'.$port, $addr) ;
            $addr = str_replace('https://', 'ssl://', $addr) ;
        } elseif (stripos($addr, 'http') === 0) {
            $port = 80 ;
        } else {
            $port = 80 ;
        }
        if (!$socket = @fsockopen($addr, $port, $errno, $errstr)) {
            $logging->log("$errstr", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false;
        } else {
            return true;
        }
    }

    protected function getSourceFilePath($flag = null){
        if (isset($this->params["source"])) { return $this->params["source"] ; }
        if (isset($flag)) { $question = "Enter $flag source file path" ; }
        else { $question = "Enter source file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getTargetFilePath($flag = null){
        if (isset($this->params["target"])) { return $this->params["target"] ; }
        if (isset($flag)) { $question = "Enter $flag target file path" ; }
        else { $question = "Enter target file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }
}