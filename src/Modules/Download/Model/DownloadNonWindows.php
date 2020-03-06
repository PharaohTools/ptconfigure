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
        if (isset($this->params['ignore-network'])) {
            $logging->log("Ignoring Network Check", $this->getModuleName());
        } else {
            $is_conn = $this->is_connected($sourceDataPath) ;
            if ($is_conn === false) {
                $logging->log("Unable to access network", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                return false;
            }
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
        $parse = parse_url($addr);
        if (stripos($addr, 'https') === 0) {
            $port = 443 ;
            $addr = str_replace($parse['host'], $parse['host'].':'.$port, $addr) ;
            $addr = str_replace('https://', 'tls://', $addr) ;
        } elseif (stripos($addr, 'http') === 0) {
            $port = 80 ;
            $addr = str_replace($parse['host'], $parse['host'].':'.$port, $addr) ;
            $addr = str_replace('http://', '', $addr) ;
        } else {
            $port = 80 ;
            $addr = str_replace($parse['host'], $parse['host'].':'.$port, $addr) ;
            $addr = str_replace('http://', '', $addr) ;
        }

        if (isset($this->params['ignore-ssl'])) {
            $logging->log("Ignoring SSL Check", $this->getModuleName());
            $scc_array = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ] ;
        } else {
            $logging->log("Enforcing SSL Check", $this->getModuleName());
            $scc_array = [] ;
        }

        $context = stream_context_create($scc_array);

        $hostname = "$addr";
        $socket = stream_socket_client($hostname, $errno, $errstr, ini_get("default_socket_timeout"), STREAM_CLIENT_CONNECT, $context);

        if (!$socket) {
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