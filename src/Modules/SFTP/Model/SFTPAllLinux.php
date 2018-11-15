<?php

Namespace Model;

class SFTPAllLinux extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $servers = array();
    protected $isNativeSSH ;
    protected $hopScript ;
    protected $hopEndEnvironment ;

    public function askWhetherToSFTPPut() {
        return $this->performSFTPPut();
    }

    public function askWhetherToSFTPGet() {
        return $this->performSFTPGet();
    }

    protected function findEnvironmentParam() {

        if (!isset($this->params["environment-name"])) {

            if (isset($this->params["env"]) && $this->params["env"] !=="") {
                $this->params["environment-name"] = $this->params["environment"] = $this->params["env"] ; }

            if (isset($this->params["environment"]) && $this->params["environment"] !=="") {
                $this->params["environment-name"] = $this->params["env"] = $this->params["environment"] ; }

            if (isset($this->params["environment-name"]) && $this->params["environment-name"] !=="") {
                $this->params["environment"] = $this->params["env"] = $this->params["environment-name"] ; } }
    }

    public function performSFTPPut() {
        if ($this->askForSFTPExecute() != true) { return false; }
        $this->findEnvironmentParam() ;
        $this->populateServers() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $sourceDataPath = $this->getSourceFilePath("local") ;
        $sourceData = $this->attemptToLoad($sourceDataPath) ;
        if (is_null($sourceData)) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("SFTP Put will cancel, no source file $sourceDataPath", $this->getModuleName());
            \Core\BootStrap::setExitCode(1) ;
            return false ;}
        $targetPath = $this->getTargetFilePath("remote", $this->getModuleName());
        $logging->log("Opening SFTP Connections...", $this->getModuleName());

        $target_scope_string = $this->findTargetScopeString();

        $res = array();
        foreach ($this->servers as $srvId => &$server) {
            if (isset($this->params["environment-box-id-include"])) {
                if ($srvId != $this->params["environment-box-id-include"] ) {
                    $logging->log("Skipping {$server["name"]} for box id Include constraint", $this->getModuleName());
                    continue ; } }
            if (isset($this->params["environment-box-id-ignore"])) {
                if ($srvId == $this->params["environment-box-id-ignore"] ) {
                    $logging->log("Skipping {$server["name"]} for box id Ignore constraint", $this->getModuleName());
                    continue ; } }
            if (isset($server["sftpObject"]) && is_object($server["sftpObject"])) {
                $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] Executing SFTP Put...", $this->getModuleName());
                $try_res = false ;
                for ($i=1; $i<=3; $i++) {
                    $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] Attempt {$i}", $this->getModuleName());
                    $try_res = $this->doSFTPPut($server["sftpObject"], $targetPath, $sourceData) ;
                    if ($try_res == true) { break ; } }
                $res[] = $try_res ;
                $msg = ($try_res === true) ? "Put Successful" : "Put Failed";
                $logging->log($msg, $this->getModuleName());
                $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] SFTP Put Completed...", $this->getModuleName()); }
            else {
                $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] Connection failure. Will not execute commands on this box...", $this->getModuleName(), LOG_FAILURE_EXIT_CODE); } }
        $logging->log("All SFTP Put Attempts Completed", $this->getModuleName());
        if (in_array(false, $res)) {
            $logging->log("Some SFTP Put Attempts Contained Failures", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false ; }
        $logging->log("All SFTP Put Attempts Completed Successfully", $this->getModuleName());
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
        $this->findEnvironmentParam() ;
        $this->populateServers();
        $sourceDataPath = $this->getSourceFilePath("remote");
        $targetPath = $this->getTargetFilePath("local");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Opening SFTP Connections...", $this->getModuleName());

        $target_scope_string = $this->findTargetScopeString();

        foreach ($this->servers as &$server) {
            $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] Executing SFTP Get...", $this->getModuleName());
            $logging->log("Remote File ".$sourceDataPath, $this->getModuleName());
            $logging->log("Local File ".$targetPath, $this->getModuleName());
            $res = $this->doSFTPGet($server["sftpObject"], $sourceDataPath, $targetPath) ;
            if ($res === false) {
                # BUG IF I SET THE LOG_FAILURE_EXIT CODE, IT DOESNT ACTUALLY DISPLAY THE ERROR MESSAGE. LOG AND ERROR SEPERATELY
                $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] SFTP Get Failed...", $this->getModuleName());
                \Core\BootStrap::setExitCode(1) ;
                return false ;
            } else {
                $logging->log("[".$server["name"]." : ".$server[$target_scope_string]."] SFTP Get Completed Successfully...", $this->getModuleName());
            }
        }
        $logging->log("All SFTP Gets Completed Successfully...", $this->getModuleName());
        return true;
    }

    protected function doSFTPPut($sftpObject, $remoteFile, $data) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $result = array() ;
        if (is_object($sftpObject)) {
            if (isset($this->isNativeSSH) && $this->isNativeSSH==true) {
                if (isset($this->params["mkdir"]) && $this->params["mkdir"]==true) {
                    $dn = dirname($remoteFile) ;
                    if ($sftpObject->_is_dir($dn)==false) {
                        $logging->log("Target directory does not exist, so creating...", $this->getModuleName());
                        $sftpObject->mkdir($dn) ; } }
                $logging->log("Attempting to put to $remoteFile", $this->getModuleName());
                $result[] = $sftpObject->put($remoteFile, $data);
                $ar = $sftpObject->getSFTPErrors() ;
                if (count($ar)==0) {
                    $hop_res = $this->runSFTPOnHop();
                    $result[] .= $hop_res; }
                else {
                    foreach ($ar as $s) {
                        $result[] = "$s\n" ; } } }
            else {
                if (isset($this->params["mkdir"]) && $this->params["mkdir"]==true) {
                    $dn = dirname($remoteFile) ;
                    if ($sftpObject->_is_dir($dn)==false) {
                        $logging->log("Target directory does not exist, so creating...", $this->getModuleName());
                        $sftpObject->mkdir($dn) ; } }
                $logging->log("Attempting to put to $remoteFile", $this->getModuleName());
                $result[] = $sftpObject->put($remoteFile, $data);
                $ar = $sftpObject->getSFTPErrors() ;
                if (count($ar)==0) {
                    $hop_res = $this->runSFTPOnHop();
                    $result[] = $hop_res; }
                else {
                    foreach ($ar as $s) {
                        $result[] = "$s\n" ; } } } }
        else {
            $logging->log("No SFTP Object, Connection likely failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            $result = false; }
        if (in_array(false, $result)) {
            $logging->log("Single SFTP Put Attempt Contained Failures", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false ; }
        $logging->log("Single SFTP Put Attempt Completed Successfully", $this->getModuleName());
        return true;
    }

    protected function runSFTPOnHop() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Looking for Hop Environment", $this->getModuleName());
        $cen = $this->getHopEnvironmentNames() ;
        if ( $cen  !== false ) {
            $logging->log("Found Hop Environment {$cen[0]}, running SSH Hop", $this->getModuleName());
            return $this->remotePushDataScriptForHop($cen[0]) ; }
        else { return true ; }
    }



    protected function remotePushDataScriptForHop($env) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $sftpFactory = new \Model\SFTP() ;
        $params["yes"] = "true" ;
        $params["guess"] = "true" ;
        $params["environment-name"] = $env ;
        $params["env-scope"] = $this->params["hop-env-scope"] ;
        $params["first-server"] = true ;
//        $params["env"] = $env_name ;
        $params["source"] = getcwd().DS.'papyrusfile' ;
        $params["target"] = '/tmp/papyrusfile' ;
        $logging->log("Forwarding local papyrus settings from local {$params["source"]} to {$params["target"]}.", $this->getModuleName()) ;
        $sftp = $sftpFactory->getModel($params, "Default") ;
        $res = $sftp->performSFTPPut() ;
        if ($res ==false ) {
            $logging->log("Forwarding failed for local papyrus settings to Hop Environment {$env}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;}
        $logging->log("Forwarding local papyrus settings to Hop Environment {$env} successful", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
        $logging->log("Using SSH to run SFTP from Hop Environment {$env} to Target Environment {$this->hopEndEnvironment}", $this->getModuleName()) ;

        $sshParams["yes"] = true ;
        $sshParams["guess"] = true ;
        $sshParams["driver"] = "seclib" ;
        $sshParams["environment-name"] = $env ;
        $sshParams["env-scope"] = $this->params["hop-env-scope"] ;
        $sshParams["port"] = (isset($papyrus["port"])) ? $papyrus["port"] : 22 ;
        $sshParams["timeout"] = (isset($papyrus["timeout"])) ? $papyrus["timeout"] : 30 ;
        $comm  = "cd /tmp ; ptconfigure sftp put -yg --env={$this->hopEndEnvironment} ";
        $comm .= "--env-scope=\"{$this->params["env-scope"]}\" ";
        $comm .= "--source=\"{$this->params["target"]}\" --target=\"{$this->params["target"]}\" ; " ;
        $comm .= " rm {$this->params["target"]} ; " ;
        $sshParams["ssh-data"] = $comm ;
        $sshFactory = new \Model\Invoke() ;
        $ssh = $sshFactory->getModel($sshParams, "Default") ;
        $res = $ssh->askWhetherToInvokeSSHData() ;
        if ($res ==false ) {
            $logging->log("Failed executing remote SFTP command via SSH", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $logging->log("Successful transfer of {$this->params["source"]} {$this->params["target"]} through SSH Hop", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;

        return true;
    }

    protected function doSFTPGet($sftpObject, $remoteFile, $localFile = false) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($sftpObject instanceof \Net_SFTP) {
            $result = $sftpObject->get($remoteFile, $localFile); }
        else {
            // @todo make this a log
            $logging->log("No SFTP Object", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            $result = false; }
        return $result ;
    }

    public function populateServers() {
        $to = $this->askForTimeout();
        if ($to == false) { return false ; }
        $port = $this->askForPort();
        if ($port == false) { return false ; }
        $sd = $this->loadServerData();
        if ($sd == false) { return false ; }
        $sshc = $this->loadSFTPConnections();
        if ($sshc == false) { return false ; }
        return true ;
    }

    // @todo this should come from invoke, code duplication
    protected function loadServerData() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        // @todo if the below is emoty we have no server to connect to so should not continue
        if (isset($this->params["servers"])) {
            // @TODO CHECK OTHER TYPES OF ARRAY LIKE JSON
            $this->servers = unserialize($this->params["servers"]);
            $srv = $this->servers ; }
        else {
            if (isset($this->params["env"]) && !isset($this->params["environment-name"] )) {
                $this->params["environment-name"] =$this->params["env"] ; }
            if (isset($this->params["hops"]) && isset($this->params["environment-name"])) {
                $logging->log("Attempting to load SFTP Hop Servers, as Hops are set...", $this->getModuleName()) ;
                $this->hopEndEnvironment = (isset($this->params["env"])) ? $this->params["env"] : null ;
                $this->hopEndEnvironment = (is_null($this->hopEndEnvironment)) ? $this->params["environment-name"] : $this->hopEndEnvironment ;
                $names = $this->getEnvironmentNames();

                // $this->hopEndEnvironment ;

                $logging->log("Attempting to use hop environment {$this->params["hops"]} to reach target environment {$this->hopEndEnvironment}", $this->getModuleName()) ;
                // @todo allow other algorithms, the best ones will be share by availability zone or literally share evenly so 5 in top and 50 in target take
                // @todo loadHopServersByAlgorithm()
                // need to get
                //   1) server/s to hop to
                //   2) target servers, for EACH of those Servers to SSH to, in a further array
                //   3) each array and their sub arrays need to have keynames or paths that already exist on the hop environment
                $this->servers[] = $this->getFirstServerOnlyAlgorithm();
                if ($this->servers ===false) {
                    $logging->log("Unable to populate servers from hop environment {$this->params["hops"]}", $this->getModuleName()) ; }
                $srv = $this->servers ; }
            else if (isset($this->params["environment-name"])) {
                $logging->log("Environment name {$this->params["environment-name"]} is set without hops, loading servers...", $this->getModuleName()) ;
                $env = $this->getEnvironment($this->params["environment-name"]) ;
                if (isset($this->params["first-server"])) {
                    $logging->log("First Server parameter is set, will only connect to first server in environment pool...", $this->getModuleName()) ;
                    $this->servers[] = $env["servers"][0]; }
                else {
                    $logging->log("Loading all servers in environment pool...", $this->getModuleName()) ;
                    $this->servers = $env["servers"]; }
//                var_dump("en:", $this->params["environment-name"], $this->servers);
                $srv = $this->servers ; }
            else {
                $logging->log("Unable to find environment name", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                $srv = false ; }

            if (!isset($this->params["environment-name"])) {
                $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
                if (count($allProjectEnvs) > 0) {
                    $question = 'Use Environments Configured in Project?';
                    $useProjEnvs = self::askYesOrNo($question, true);
                    if ($useProjEnvs == true) {
                        $this->servers = new \ArrayObject($allProjectEnvs);
                        // @todo need to ask a question here, this wont work
                        // give them an array option od environment name
                        $srv = false ; } }
                else {
                    $srv = $this->askForServerTarget(); } } }

        if (is_array($srv) && count($srv)>0) { return $srv ; }
        $logging->log("Unable to populate servers for environment", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
        return false ;
    }

    protected function getFirstServerOnlyAlgorithm() {
        // @todo probably move the available algorithms to their own classes
        $env = $this->getNextHopEnvironment();
//        $env =$this->getEnvironment($env_name) ;
        $sv_zero = $env["servers"][0] ;
//        var_dump('svz:', $env["servers"][0]) ;
        return $sv_zero ;
    }

    protected function getEnvironment($env_name) {
        $envs = \Model\AppConfig::getProjectVariable("environments");
        foreach ($envs as $env) {
            if ($env_name == $env["any-app"]["gen_env_name"]){
                return $env ; } }
        return false;
    }

    protected function getEnvironmentNames() {
        $envs = \Model\AppConfig::getProjectVariable("environments");
        $eNames = array();
        foreach ($envs as $envKey => $env) {
            $envName = $env["any-app"]["gen_env_name"];
            $eNames[] = $envName; }
        return $eNames;
    }

    protected function getHopEnvironmentNames() {
        if (isset($this->params["hops"])) {
            return explode(',', $this->params["hops"]); }
        else {
            return false ; }
    }

    protected function getNextHopEnvironment() {
        $allhe = $this->getHopEnvironmentNames() ;
        if ($allhe !== false) {
            $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
            foreach ($allProjectEnvs as $env) {
//                var_dump("en:", $allhe[0], $env["any-app"]["gen_env_name"]) ;
                if ($allhe[0] == $env["any-app"]["gen_env_name"]){
                    return $env ; } }
            return false ; }
        else {
            return false ; }
    }

    protected function loadSFTPConnections() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to load SFTP connections...", $this->getModuleName());
        foreach ($this->servers as $srvId => &$server) {
            if (isset($this->params["environment-box-id-include"])) {
                if ($srvId != $this->params["environment-box-id-include"] ) {
                    $logging->log("Skipping {$server["name"]} for box id Include constraint", $this->getModuleName());
                    continue ; } }
            if (isset($this->params["environment-box-id-ignore"])) {
                if ($srvId == $this->params["environment-box-id-ignore"] ) {
                    $logging->log("Skipping {$server["name"]} for box id Ignore constraint", $this->getModuleName());
                    continue ; } }
            $attempt = $this->attemptSFTPConnection($server) ;

            $target_scope_string = $this->findTargetScopeString();

            if ($attempt == null) {
                $logging->log("Connection to Server {$server[$target_scope_string]} failed.", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                $server["sftpObject"] = null ; }
            else {
                $logging->log("Connection to Server {$server[$target_scope_string]} successful.", $this->getModuleName());
                $server["sftpObject"] = $attempt ; } }

            return true;
    }

    protected function findTargetScopeString() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (isset($this->params["hops"])) {

            if (isset($this->params["hop-env-scope"]) && $this->params["hop-env-scope"] == "public") {
                $logging->log("Using a hop env scope of public", $this->getModuleName());
                $target_scope_string = "target_public" ; }
            else if (isset($this->params["hop-env-scope"]) && $this->params["hop-env-scope"] == "private") {
                $logging->log("Using a hop env scope of private", $this->getModuleName());
                $target_scope_string = "target_private" ; }
            else {
                $logging->log("Using default hop env scope", $this->getModuleName());
                $target_scope_string = "target" ; }
        }  else {

            if (isset($this->params["env-scope"]) && $this->params["env-scope"] == "public") {
                $logging->log("Using an env scope of public", $this->getModuleName());
                $target_scope_string = "target_public" ; }
            else if (isset($this->params["env-scope"]) && $this->params["env-scope"] == "private") {
                $logging->log("Using an env scope of private", $this->getModuleName());
                $target_scope_string = "target_private" ; }
            else {
                $logging->log("Using default env scope", $this->getModuleName());
                $target_scope_string = "target" ; } }

        return $target_scope_string ;
    }

    // @todo it currently looks for both pword and password lets stick to one
    protected function attemptSFTPConnection($server) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $askpass = $this->askForServerPassword(true) ;
        if ($askpass !== false) {
            $logging->log("Overriding Stored Password or Key.", $this->getModuleName()) ;
            $pword = $askpass ;
        } else {
            $pword = (isset($server["pword"])) ? $server["pword"] : false;
            $pword = (isset($server["password"])) ? $server["password"] : $pword;
        }
        // @ todo native not working
        if (1 === 0) {
            $this->isNativeSSH = true ;
            $sftpFactory = new \Model\SFTP();
            $sftp = $sftpFactory->getModel($this->params, "NativeWrapper" ) ;
            $sftp->target = $server["target"] ;
            $sftp->port = $this->params["port"];
            $sftp->timeout = $this->params["timeout"] ;
            if ($sftp->login($server["user"], $pword) == true) { return $sftp; }
            return null; }
        else {
            if (!class_exists('Net_SSH2')) {
                // Always load SSH2 class from here as SFTP class tries to load it wrongly
                $srcFolder =  str_replace(DS."Model", DS."Libraries", dirname(__FILE__) ) ;
                $ssh2File = $srcFolder.DS."seclib".DS."Net".DS."SSH2.php" ;
                $path = dirname(__DIR__).DS.'Libraries'.DS.'seclib'.DS ;
                set_include_path(get_include_path() . PATH_SEPARATOR . $path);
                require_once($ssh2File) ; }
            if (!class_exists('Net_SFTP')) {
                $srcFolder =  str_replace(DS."Model", DS."Libraries", dirname(__FILE__) ) ;
                $sftpFile =$srcFolder.DS."seclib".DS."Net".DS."SFTP.php" ;
                $path = dirname(__DIR__).DS.'Libraries'.DS.'seclib'.DS ;
                set_include_path(get_include_path() . PATH_SEPARATOR . $path);
                require_once($sftpFile) ; }

            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);

            $target_scope_string = $this->findTargetScopeString();

            $sftp = new \Net_SFTP($server[$target_scope_string], $this->params["port"], $this->params["timeout"]);
            $pword = $this->getKeyIfAvailable($pword);
            if ($sftp->login($server["user"], $pword) == true) { return $sftp; }
            return null; }
    }

    protected function getKeyIfAvailable($pword) {
        if (substr($pword, 0, 4) == 'KS::') {
            $ksf = new SshKeyStore();
            $ks = $ksf->getModel(array("key" => $pword, "guess" => "true")) ;
            $pword = $ks->findKey() ; }
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
*    changed to PHARAOHPROMPT     *
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
       if (isset($this->params["timeout"])) {
            return $this->params["timeout"] ; }
        if (isset($this->params["guess"])) {
            $this->params["timeout"] = 100 ;
            return $this->params["timeout"]; }
        $question = 'Please Enter SSH Timeout in seconds';
        $input = self::askForInput($question, true) ;
        $this->params["timeout"] = $input ;
    }

    protected function askForPort(){
        if (isset($this->params["port"])) {
            return $this->params["port"] ; }
        if (isset($this->params["guess"])) {
            $this->params["port"] = 22 ;
            return $this->params["port"] ; }
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

    protected function askForServerPassword($silent = false)	{
        if (isset($this->params["ssh-key-path"])) {
            return $this->params["ssh-key-path"]; }
        else if (isset($this->params["key-path"])) {
            return $this->params["key-path"]; }
        else if (isset($this->params["path"])) {
            return $this->params["path"]; }
        else if (isset($this->params["ssh-pass"])) {
            return $this->params["ssh-pass"]; }
        else if (isset($this->params["pass"])) {
            return $this->params["pass"]; }
        if ($silent !== true) {
            $question = 'Please Enter Server Password or Key Path';
            $input = self::askForInput($question);
            return $input;
        }
        return false ;
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