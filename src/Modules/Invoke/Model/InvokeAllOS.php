<?php

Namespace Model;

class InvokeAllOS extends Base {

	// Compatibility
	public $os = array("any");
	public $linuxType = array("any");
	public $distros = array("any");
	public $versions = array("any");
	public $architectures = array("any");

	// Model Group
	public $modelGroup = array("Default");

	protected $servers = array();
	protected $sshCommands;
	protected $isNativeSSH;
    protected $hopScript ;
    protected $hopEndEnvironment ;

	public function askWhetherToInvokeSSHShell() {
		return $this->performInvokeSSHShellWithHops();
	}

	public function askWhetherToInvokeSSHScript() {
        if (isset($this->params["hops"])) {
            return $this->performInvokeSSHScriptWithHops() ; }
        else {
            return $this->performInvokeSSHScript() ; }
	}

	public function askWhetherToInvokeSSHData() {
        if (isset($this->params["hops"])) {
            return $this->performInvokeSSHDataWithHops() ; }
        else {
            return $this->performInvokeSSHData() ; }
	}

	public function performInvokeSSHShellWithHops() {
		if ($this->askForSSHShellExecute() != true) {
			return false; }
		$this->populateServers();
        $commandExecution = true;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;
        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            while ($commandExecution == true) {
                $command = $this->askForACommand();
                if ($command == false) {
                    $commandExecution = false; }
                else {
                    foreach ($this->servers as &$server) {
                        $logging->log( "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Executing $command...", $this->getModuleName()) ;

                        $out = $this->doSSHCommand($server["ssh2Object"], $command);
                        echo $out["data"] ;
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] $command Completed...", $this->getModuleName()) ;
                        if ($out["rc"] != 0) {
                            $logging->log("Command failed on remote with exit code {$out["rc"]}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                            return false ; }

                        $logging->log( "[" . $server["name"] . " : " . $server[$target_scope_string] . "] $command Completed...", $this->getModuleName()) ; } } } }
        else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        $logging->log("Shell Completed", $this->getModuleName()) ;
		return true;
	}

    public function performInvokeSSHScript() {
        if ($this->askForSSHScriptExecute() != true) {
            return false; }
        $scriptLoc = $this->askForScriptLocation();
        $this->populateServers();
        $this->sshCommands = file_get_contents($scriptLoc);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;

        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
                foreach ($this->servers as &$server) {
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Executing $this->sshCommands...", $this->getModuleName()) ;

                        $out = $this->doSSHCommand($server["ssh2Object"], $this->sshCommands);
                        echo $out["data"] ;
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] SSH Command Completed...", $this->getModuleName()) ;
                        if ($out["rc"] != 0) {
                            $logging->log("Command failed on remote with exit code {$out["rc"]}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                            return false ; } }
                    else {
                        $logging->log( "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; }
                }
        } else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        $logging->log("Script by SSH Completed", $this->getModuleName()) ;
        return true;
    }

    public function performInvokeSSHData() {
        if ($this->askForSSHDataExecute() != true) {
            return false; }
        $this->populateServers();
        $this->sshCommands = $this->getSSHCommandsForThisStage("data") ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;

        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
                foreach ($this->servers as &$server) {
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Executing $this->sshCommands...", $this->getModuleName()) ;
                        $rc = $this->doSSHCommand($server["ssh2Object"], $this->sshCommands);
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] SSH Commands Completed...", $this->getModuleName()) ;
                        if ($rc !== 0) {
                            $logging->log("Command failed on remote with exit code {$rc}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                            return false ; } }
                    else {
                        $logging->log( "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; } }
        } else {
            $logging->log("No successful connections available", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $logging->log("Data by SSH Completed", $this->getModuleName()) ;
        return true;
    }

    public function performInvokeSSHScriptWithHops() {
        if ($this->askForSSHScriptExecute() != true) {
            return false; }
        $ps = $this->populateServers();
        $this->sshCommands = $this->getSSHCommandsForThisStage("script") ;
        if ($ps == false) { return false ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;

        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            foreach ($this->sshCommands as $sshCommand) {

                foreach ($this->servers as &$server) {
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Forwarding script {$this->hopScript}...", $this->getModuleName()) ;
                        $this->remotePushDataScriptForHop($this->hopScript, $server) ;
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Executing $sshCommand...", $this->getModuleName()) ;
                        echo $this->doSSHCommand($server["ssh2Object"], $sshCommand);
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] $sshCommand Completed...", $this->getModuleName()) ; }
                    else {
                        $logging->log( "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; } } }}
        else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        $logging->log("Script by SSH Completed", $this->getModuleName()) ;
        return true;
    }

	public function performInvokeSSHDataWithHops() {
		if ($this->askForSSHScriptExecute() != true) {
			return false; }
        $ps = $this->populateServers();
        $this->sshCommands = $this->getSSHCommandsForThisStage("data") ;
		if ($ps == false) { return false ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;

        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            foreach ($this->sshCommands as $sshCommand) {
//                var_dump('sc', $sshCommand) ;
                foreach ($this->servers as &$server) {
//                    var_dump('sv', $server) ;
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Forwarding script {$this->hopScript}...", $this->getModuleName()) ;
                        $this->remotePushDataScriptForHop($this->hopScript, $server) ;
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Executing $sshCommand...", $this->getModuleName()) ;
                        $cur_res = $this->doSSHCommand($server["ssh2Object"], $sshCommand);
                        if (isset($cur_res) && $cur_res == false) {
                            $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Command failed...", $this->getModuleName()) ; }
                        else {
                            echo $cur_res ; }
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] $sshCommand Completed...", $this->getModuleName()) ; }
                    else {
                        $logging->log(  "[" . $server["name"] . " : " . $server[$target_scope_string] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; } } } }
        else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }

        $logging->log( "Data by SSH Completed", $this->getModuleName()) ;;
		return true;
	}

    protected function getSSHCommandsForThisStage($type) {
        // @todo lots of logging
        //if (isset($this->params["hops"])) {
            if ($type=="data") {
                $cen = $this->getHopEnvironmentNames() ;
                if ($cen !== false) {

                    $data = $this->askForSSHData();
                    $file = $this->turnDataIntoScriptForHop($data) ;
                    $this->hopScript = $file ;
                        $cli_commands = array(
                        "cp /tmp/papyrusfile .",
                        'ptconfigure invoke script -yg --env="'.$this->hopEndEnvironment.'" --hop-env-="'.$this->hopEndEnvironment.'" --ssh-script="'.$file.'" ');
                    return $cli_commands ; }
                else {
                    $data = $this->askForSSHData();
//                    $lines = explode("\n", $data);
//                    $lines[] = "\n" ;
//                    return $lines ;
                    return $data ;
                }  }
            else if ($type=="script") {
                $scriptLoc = $this->askForScriptLocation();
                $shc = explode(PHP_EOL, file_get_contents($scriptLoc));
                return array($shc) ; }
            else {
                return false; }
    }

    protected function turnDataIntoScriptForHop($data) {
        $long_file_name = $this->tempfileFromCommand($data) ;
        // create a file containing the data
        // return a super hashed filename
        return $long_file_name ;
    }

    protected function remotePushDataScriptForHop($script_file, $server) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $sftpFactory = new \Model\SFTP() ;
        $logging->log("Forwarding local papyrus settings ", $this->getModuleName()) ;
        $params["yes"] = "true" ;
        $params["guess"] = "true" ;
        $params["servers"] = serialize(array($server)) ;
//        $params["env"] = $env_name ;
        $params["source"] = getcwd().DS.'papyrusfile' ;
        $params["target"] = '/tmp/papyrusfile' ;
        $sftp = $sftpFactory->getModel($params, "Default") ;
        $res = $sftp->performSFTPPut() ;
        if ($res ==false ) {
            $logging->log("Forwarding failed for local papyrus settings", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;}
        $logging->log("Forwarding Hop Data script ", $this->getModuleName()) ;
        $params["source"] = $script_file ;
        $params["target"] = $script_file ;
        $sftp = $sftpFactory->getModel($params, "Default") ;
        $res = $sftp->performSFTPPut() ;
        if ($res ==false ) {
            $logging->log("Forwarding failed for Hop Data script ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;}
    }

	public function populateServers() {
		$to = $this->askForTimeout();
        if ($to == false) { return false ; }
        $port = $this->askForPort();
        if ($port == false) { return false ; }
        $sd = $this->loadServerData();
        if ($sd == false) { return false ; }
        $sshc = $this->loadSSHConnections();
        if ($sshc == false) { return false ; }
        return true ;
	}

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
                $logging->log("Attempting to load SSH Hop Servers, as Hops are set...", $this->getModuleName()) ;
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
                $names = $this->getEnvironmentNames();
                $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
                $this->servers = $allProjectEnvs[$names[$this->params["environment-name"]]]["servers"];
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
            $eNames[ $envName ] = $envKey; }
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

    protected function loadSSHConnections() {
		$loggingFactory = new \Model\Logging();
		$logging = $loggingFactory->getModel($this->params);
		$logging->log("Attempting to load SSH connections...", $this->getModuleName()) ;
		foreach ($this->servers as $srvId => &$server) {
			if (isset($this->params["environment-box-id-include"])) {
				if ($srvId != $this->params["environment-box-id-include"]) {
					$logging->log("Skipping {$server["name"]} for box id Include constraint", $this->getModuleName()) ;
					continue; } }
			if (isset($this->params["environment-box-id-ignore"])) {
				if ($srvId == $this->params["environment-box-id-ignore"]) {
					$logging->log("Skipping {$server["name"]} for box id Ignore constraint", $this->getModuleName()) ;
					continue; } }
			$attempt = $this->attemptSSH2Connection($server);
			if ($attempt == null || $attempt == false) {
                $logging->log("Connection to Server {$this->findTarget($server)} failed. Removing from pool.", $this->getModuleName()) ;
                unset($this->servers[$srvId]);
                return false ;}
            else {
				$server["ssh2Object"] = $attempt;
				$logging->log("Connection to Server {$this->findTarget($server)} successful.", $this->getModuleName()) ;
//				echo $this->changeBashPromptToPharaoh($server["ssh2Object"]);
//				if (!isset($this->isNativeSSH) || (isset($this->isNativeSSH) && $this->isNativeSSH != true)) {
//				}
				$rc = $this->doSSHCommand($server["ssh2Object"], 'echo "Pharaoh Remote SSH on ...' . $this->findTarget($server) . '"', true);
                echo ($rc == 0) ? 'Success ' : 'Failure '; } }
		return true;
	}

    protected function attemptSSH2Connection($server) {
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
        $invokeFactory = new \Model\Invoke() ;
        $serverObj = $invokeFactory->getModel($this->params, "Server") ;
        $target = $this->findTarget($server) ;
//        var_dump($server) ;
        $serverObj->init($target, $server['user'], $pword, isset($server['port']) ? $server['port'] : 22);
//      $server = new \Invoke\Server();
//		$driverString = isset($this->params["driver"]) ? $this->params["driver"] : 'seclib';
//      $options = array("os" => "DriverBashSSH", "native" => "DriverNativeSSH", "seclib" => "DriverSecLib") ;
        $driverString = $this->findDriver() ;
        $driver = $invokeFactory->getModel($this->params, $driverString) ;
        $driver->setServer($serverObj);
        $serverObj->setDriver($driver);
        if ($serverObj->connect() == true ){ return $serverObj; }
//        var_dump($serverObj) ;
        return false;
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

    protected function findTarget($server) {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $target_scope_string = $this->findTargetScopeString() ;

        if (isset($target_scope_string)) {
            return $server[$target_scope_string] ; }

        return false ;
    }

    protected function findDriver() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $optionsKeep = array(
            "os" => "DriverBashSSH",
            "bash" => "DriverBashSSH",
            "bashssh" => "DriverBashSSH",
            "BashSSH" => "DriverBashSSH",
            "osnative" => "DriverBashSSH",
            "native" => "DriverNativeSSH",
            "phpnative" => "DriverNativeSSH",
            "php" => "DriverNativeSSH",
            "seclib" => "DriverSecLib"
        ) ;
        $optionsAsk = array_keys($optionsKeep) ;
        $system = new \Model\SystemDetectionAllOS() ;
        if (isset($this->params["driver"]) && in_array($this->params["driver"], $optionsAsk) ) {
            if (in_array($system->os, array("WINNT", "Windows")) && $this->params["driver"] == "os") {
                $logging->log("Windows does not support requested OS level SSH driver, switching to seclib...", $this->getModuleName()) ;
                return "DriverSecLib" ; }
            $logging->log("Using requested {$optionsKeep[$this->params["driver"]]} driver...", $this->getModuleName()) ;
            return $optionsKeep[$this->params["driver"]]; }
        if (isset($this->params["guess"]) && $this->params["guess"] == true) {
            if (in_array($system->os, array("WINNT", "Windows"))) {
                $logging->log("Using default driver for Windows systems, Seclib SSH driver...", $this->getModuleName()) ;
                return "DriverSecLib" ; }
//            @todo fix bash
//            $logging->log("Using default driver for non-windows systems, Shell/OS Native SSH driver...");
//            return "DriverBashSSH";
            $logging->log("Using default driver for non-windows systems, Seclib SSH driver...", $this->getModuleName()) ;
            return "DriverSecLib"; }
        $question = 'Which SSH Driver should I use?';
        $ofound = self::askForArrayOption($question, $optionsAsk);
        $ofound = $optionsKeep[$ofound] ;
        return $ofound ;
    }

	protected function askForSSHShellExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true;  }
		$question = 'Invoke SSH Shell on Server group?';
		return self::askYesOrNo($question);
	}

	protected function askForSSHScriptExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true; }
		$question = 'Invoke SSH Script on Server group?';
		return self::askYesOrNo($question);
	}

	protected function askForSSHDataExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true;}
		$question = 'Invoke SSH Data on Server group?';
		return self::askYesOrNo($question);
	}

	protected function askForScriptLocation() {
        if (isset($this->params["ssh-script"])) {
            return $this->params["ssh-script"]; }
        else if (isset($this->params["script"])) {
            return $this->params["script"]; }
		$question = 'Enter Location of bash script to execute';
		return self::askForInput($question, true);
	}

	protected function askForSSHData() {
		if (isset($this->params["ssh-data"])) {
			return $this->params["ssh-data"]; }
        if (isset($this->params["data"])) {
            return $this->params["data"]; }
		$question = 'Enter data to execute via SSH';
		return self::askForInput($question, true);
	}

	protected function askForServerInfo() {
		$startQuestion = <<<QUESTION
***********************************
*    The user that you use here   *
*  will have their command prompt *
*    changed to PHARAOHPROMPT     *
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
			if (count($this->servers) < 1) {
				$question .= "You need to enter at least one server\n"; }
			$serverAddingExecution = self::askYesOrNo($question);
		}
	}

	protected function askForTimeout() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
		if (isset($this->params["timeout"])) {
			return $this->params["timeout"]; }
		if (isset($this->params["guess"])) {
			$this->params["timeout"] = 100;
            $logging->log("Guessing an SSH Timeout value of 100 seconds", $this->getModuleName()) ;
			return $this->params["timeout"]; }
		$question = 'Please Enter SSH Timeout in seconds';
		$input = self::askForInput($question, true);
		$this->params["timeout"] = $input;
        return $this->params["timeout"];
	}

	protected function askForPort() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
		if (isset($this->params["port"])) {
			return $this->params["port"]; }
		if (isset($this->params["guess"])) {
            $logging->log("Guessing an SSH Port value of 22", $this->getModuleName()) ;
            $this->params["port"] = 22;
			return $this->params["port"]; }
		$question = 'Please Enter remote SSH Port';
		$input = self::askForInput($question, true);
		$this->params["port"] = $input;
        return $this->params["port"];
	}

	protected function askForServerTarget() {
        if (isset($this->params["ssh-target"])) {
            return $this->params["ssh-target"];	}
        if (isset($this->params["target"])) {
            $this->params["ssh-target"] = $this->params["target"];
            return $this->params["target"];	}
        if (isset($this->params["targets"])) {
            $this->params["targets"] = $this->params["target"];
            return $this->params["targets"];	}
		$question = 'Please Enter SSH Server Target Host Name/IP';
		$input = self::askForInput($question, true);
		return $input;
	}

	protected function askForServerUser() {
        if (isset($this->params["ssh-user"])) {
            return $this->params["ssh-user"]; }
        if (isset($this->params["user"])) {
            $this->params["ssh-user"] = $this->params["user"];
            return $this->params["ssh-user"]; }
		$question = 'Please Enter SSH User';
        $this->params["ssh-user"] = self::askForInput($question, true);
		return $this->params["ssh-user"] ;
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

	protected function askForACommand() {
		$question = 'Enter command to be executed on remote servers? Enter none to close connection and end program';
		$input = self::askForInput($question);
		return ($input == "") ? false : $input;
	}

	protected function changeBashPromptToPharaoh($sshObject) {
		$command = 'echo "export PS1=PHARAOHPROMPT" > ~/.bash_login ';
		return $sshObject->exec("$command\n");
	}

	protected function doSSHCommand($sshObject, $command, $first = null) {
        $out = $sshObject->exec($command);
        echo $out["data"] ;
		return $out['rc'] ;
	}

}