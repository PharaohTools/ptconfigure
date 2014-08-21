<?php

Namespace Model;

class SFTPAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $servers = array();

    public function askWhetherToSFTPPut() {
        return $this->performSFTPPut();
    }

    public function askWhetherToSFTPGet() {
        return $this->performSFTPGet();
    }

    public function performSFTPPut() {
        if ($this->askForSFTPExecute() != true) { return false; }
        $this->populateServers() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $sourceDataPath = $this->getSourceFilePath("local") ;
        $sourceData = $this->attemptToLoad($sourceDataPath) ;
        if (is_null($sourceData)) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("SFTP Put will cancel, no source file") ;
            return false ;}
        $targetPath = $this->getTargetFilePath("remote") ;
        $logging->log("Opening SFTP Connections...") ;
        foreach ($this->servers as $srvId => &$server) {
            if (isset($this->params["environment-box-id-include"])) {
                if ($srvId != $this->params["environment-box-id-include"] ) {
                    $logging->log("Skipping {$$server["name"]} for box id Include constraint") ;
                    continue ; } }
            if (isset($this->params["environment-box-id-ignore"])) {
                if ($srvId == $this->params["environment-box-id-ignore"] ) {
                    $logging->log("Skipping {$$server["name"]} for box id Ignore constraint") ;
                    continue ; } }
            $logging->log("[".$server["target"]."] Executing SFTP Put...")  ;
            $logging->log($this->doSFTPPut($server["sftpObject"], $targetPath, $sourceData)) ;
            $logging->log("[".$server["target"]."] SFTP Put Completed...") ; }
        $logging->log("All SFTP Puts Completed");
        return true;
    }

    protected function attemptToLoad($sourceDataPath){
        if (file_exists($sourceDataPath)) {
            return file_get_contents($sourceDataPath) ; }
        else {
            return null ; }
    }

    public function performSFTPGet() {
        if ($this->askForSFTPExecute() != true) { return false; }
        $this->populateServers();
        $sourceDataPath = $this->getSourceFilePath("remote");
        $targetPath = $this->getTargetFilePath("local");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Opening SFTP Connections...");
        foreach ($this->servers as &$server) {
            $logging->log("[".$server["target"]."] Executing SFTP Get...");
            $logging->log($this->doSFTPGet($server["sftpObject"], $sourceDataPath, $targetPath)) ;
            $logging->log("[".$server["target"]."] SFTP Get Completed..."); }
        $logging->log("All SFTP Gets Completed");
        return true;
    }

    protected function doSFTPPut($sftpObject, $remoteFile, $data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $result = "" ;
        if ($sftpObject instanceof \Net_SFTP) {
            if (isset($this->params["mkdir"]) && $this->params["mkdir"]==true) {
                $dn = dirname($remoteFile) ;
                if ($sftpObject->_is_dir($dn)==false) {
                    $logging->log("Target directory does not exist, so creating...");
                    $sftpObject->mkdir($dn) ; } }
            $result .= $sftpObject->put($remoteFile, $data);
            $ar = $sftpObject->getSFTPErrors() ;
            foreach ($ar as $s) {
                $result .= "$s\n" ; } }
        else {
            // @todo make this a log
            $logging->log("No SFTP Object, Connection likely failed");
            $result = false; }
        return $result ;
    }

    protected function doSFTPGet($sftpObject, $remoteFile, $localFile = false) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($sftpObject instanceof \Net_SFTP) {
            $result = $sftpObject->get($remoteFile, $localFile); }
        else {
            // @todo make this a log
            $logging->log("No SFTP Object");
            $result = false; }
        return $result ;
    }

    public function populateServers() {
        $this->askForTimeout();
        $this->askForPort();
        $this->loadServerData();
        $this->loadSFTPConnections();
    }

    protected function loadServerData() {
        $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
        if (isset($this->params["servers"])) {
            $this->servers = unserialize($this->params["servers"]); }
        else if (isset($this->params["environment-name"])) {
            $names = $this->getEnvironmentNames($allProjectEnvs) ;
            $this->servers = $allProjectEnvs[$names[$this->params["environment-name"]]]["servers"]; }
        else if (count($allProjectEnvs) > 0) {
            $question = 'Use Environments Configured in Project?';
            $useProjEnvs = self::askYesOrNo($question, true);
            if ($useProjEnvs == true ) {
                $this->servers = new \ArrayObject($allProjectEnvs) ;
                return; } }
        else {
            $this->askForServerInfo(); }
    }

    protected function getEnvironmentNames($envs) {
        $eNames = array() ;
        foreach ($envs as $envKey => $env) {
            $envName = $env["any-app"]["gen_env_name"] ;
            $eNames[$envName] = $envKey ; }
        return $eNames ;
    }

    protected function loadSFTPConnections() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to load SFTP connections...");
        foreach ($this->servers as &$server) {
            $attempt = $this->attemptSFTPConnection($server) ;
            if ($attempt == null) {
                $logging->log("Connection to Server {$server["target"]} failed.");
                $server["sftpObject"] = null ; }
            else {
                $server["sftpObject"] = $attempt ; } }
        return true;
    }

    // @todo it currently looks for both pword and password lets stick to one
    protected function attemptSFTPConnection($server) {
        if (!class_exists('Net_SSH2')) {
            // Always load SSH2 class from here as SFTP class tries to load it wrongly
            $srcFolder =  str_replace("/Model", "/Libraries", dirname(__FILE__) ) ;
            $ssh2File = $srcFolder."/seclib/Net/SSH2.php" ;
            require_once($ssh2File) ; }
        if (!class_exists('Net_SFTP')) {
            $srcFolder =  str_replace("/Model", "/Libraries", dirname(__FILE__) ) ;
            $sftpFile = $srcFolder."/seclib/Net/SFTP.php" ;
            require_once($sftpFile) ; }
        $sftp = new \Net_SFTP($server["target"], $this->params["port"], $this->params["timeout"]);
        $pword = (isset($server["pword"])) ? $server["pword"] : false ;
        $pword = (isset($server["password"])) ? $server["password"] : $pword ;
        $pword = $this->getKeyIfAvailable($pword);
        if ($sftp->login($server["user"], $pword) == true) { return $sftp; }
        return null;
    }

    protected function getKeyIfAvailable($pword) {
        if (substr($pword, 0, 1) == '~') {
            $home = $_SERVER['HOME'] ;
            $pword = str_replace('~', $home, $pword) ; }
        if (file_exists($pword)) {
            if (!class_exists('Crypt_RSA')) {
                $srcFolder =  str_replace("/Model", "/Libraries", dirname(__FILE__) ) ;
                $rsaFile = $srcFolder."/seclib/Crypt/RSA.php" ;
                require_once($rsaFile) ; }
            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents($pword));
            return $key ; }
        return $pword ;
    }

    protected function askForSFTPExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'SFTP on Server group?';
        return self::askYesOrNo($question);
    }

    protected function askForServerInfo(){
        $startQuestion = <<<QUESTION
***********************************
*   Due to a software limitation, *
*    The user that you use here   *
*  will have their command prompt *
*    changed to PHAROAHPROMPT     *
*  ... I'm working on that one... *
*  Exit program to stop (CTRL+C)  *
***********************************
Enter Server Info:

QUESTION;
        echo $startQuestion;
        $serverAddingExecution = true;
        while ($serverAddingExecution == true) {
            $server = array();
            $server["target"] = $this->askForServerTarget();
            $server["user"] = $this->askForServerUser();
            $server["pword"] = $this->askForServerPassword();
            $this->servers[] = $server;
            $question = 'Add Another Server?';
            if ( count($this->servers)<1) { $question .= "You need to enter at least one server\n"; }
            $serverAddingExecution = self::askYesOrNo($question); }
    }

    protected function askForTimeout(){
        if (isset($this->params["timeout"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["timeout"] = 100 ;
            return ; }
        $question = 'Please Enter SSH Timeout in seconds';
        $input = self::askForInput($question, true) ;
        $this->params["timeout"] = $input ;
    }

    protected function askForPort(){
        if (isset($this->params["port"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["port"] = 22 ;
            return ; }
        $question = 'Please Enter remote SSH Port';
        $input = self::askForInput($question, true) ;
        $this->params["port"] = $input ;
    }

    protected function askForServerTarget(){
        $question = 'Please Enter SSH Server Target Host Name/IP';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    protected function askForServerUser(){
        $question = 'Please Enter SSH User';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    protected function askForServerPassword(){
        $question = 'Please Enter Server Password or Key Path';
        $input = self::askForInput($question) ;
        return  $input ;
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