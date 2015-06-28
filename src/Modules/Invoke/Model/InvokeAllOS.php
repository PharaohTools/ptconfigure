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

	private $servers = array();
	private $sshCommands;
	protected $isNativeSSH;

	public function askWhetherToInvokeSSHShell() {
		return $this->performInvokeSSHShell();
	}

	public function askWhetherToInvokeSSHScript() {
		return $this->performInvokeSSHScript();
	}

	public function askWhetherToInvokeSSHData() {
		return $this->performInvokeSSHData();
	}

	public function performInvokeSSHShell() {
		if ($this->askForSSHShellExecute() != true) {
			return false; }
		$this->populateServers();
		$commandExecution = true;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            while ($commandExecution == true) {
                $command = $this->askForACommand();
                if ($command == false) {
                    $commandExecution = false; }
                else {
                    foreach ($this->servers as &$server) {
                        $logging->log( "[" . $server["target"] . "] Executing $command...", $this->getModuleName()) ;
                        echo $this->doSSHCommand($server["ssh2Object"], $command);
                        $logging->log( "[" . $server["target"] . "] $command Completed...", $this->getModuleName()) ; } } } }
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
		$this->sshCommands = explode("\n", file_get_contents($scriptLoc));
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            foreach ($this->sshCommands as $sshCommand) {
                foreach ($this->servers as &$server) {
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["target"] . "] Executing $sshCommand...", $this->getModuleName()) ;
                        echo $this->doSSHCommand($server["ssh2Object"], $sshCommand);
                        $logging->log(  "[" . $server["target"] . "] $sshCommand Completed...", $this->getModuleName()) ; }
                    else {
                        $logging->log( "[" . $server["target"] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; } } }}
        else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        $logging->log("Script by SSH Completed", $this->getModuleName()) ;
		return true;
	}

	public function performInvokeSSHData() {
		if ($this->askForSSHDataExecute() != true) {
			return false; }
		$data = $this->askForSSHData();
		$this->populateServers();
		$this->sshCommands = explode("\n", $data);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($this->servers) > 0) {
            $logging->log("Opening CLI...", $this->getModuleName()) ;
            foreach ($this->sshCommands as $sshCommand) {
                foreach ($this->servers as &$server) {
                    if (isset($server["ssh2Object"]) && is_object($server["ssh2Object"])) {
                        $logging->log(  "[" . $server["target"] . "] Executing $sshCommand...", $this->getModuleName()) ;
                        echo $this->doSSHCommand($server["ssh2Object"], $sshCommand);
                        $logging->log(  "[" . $server["target"] . "] $sshCommand Completed...", $this->getModuleName()) ; }
                    else {
                        $logging->log(  "[" . $server["target"] . "] Connection failure. Will not execute commands on this box...", $this->getModuleName()) ; } } }        }
        else {
            $logging->log("No successful connections available", $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }

        $logging->log( "Data by SSH Completed", $this->getModuleName()) ;;
		return true;
	}

	public function populateServers() {
		$this->askForTimeout();
		$this->askForPort();
		$this->loadServerData();
		$this->loadSSHConnections();
	}

	private function loadServerData() {
        // @todo if the below is emoty we have no server to connect to so should not continue
		$allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
		if (isset($this->params["servers"])) {
			$this->servers = unserialize($this->params["servers"]); }
        else {
            if (isset($this->params["env"]) && !isset($this->params["environment-name"] )) {
                $this->params["environment-name"] =$this->params["env"] ; }
			if (isset($this->params["environment-name"])) {
				$names = $this->getEnvironmentNames($allProjectEnvs);
				$this->servers = $allProjectEnvs[ $names[ $this->params["environment-name"] ] ]["servers"]; }
            else {
				if (count($allProjectEnvs) > 0) {
					$question = 'Use Environments Configured in Project?';
					$useProjEnvs = self::askYesOrNo($question, true);
					if ($useProjEnvs == true) {
						$this->servers = new \ArrayObject($allProjectEnvs);
                        return; } }
                else {
					$this->askForServerInfo(); } } }
	}

	private function getEnvironmentNames($envs) {
		$eNames = array();
		foreach ($envs as $envKey => $env) {
			$envName = $env["any-app"]["gen_env_name"];
			$eNames[ $envName ] = $envKey; }
		return $eNames;
	}

	private function loadSSHConnections() {
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
                $logging->log("Connection to Server {$server["target"]} failed. Removing from pool.", $this->getModuleName()) ;
                unset($this->servers[$srvId]);
                return false ;}
            else {
				$server["ssh2Object"] = $attempt;
				$logging->log("Connection to Server {$server["target"]} successful.", $this->getModuleName()) ;
//				echo $this->changeBashPromptToPharaoh($server["ssh2Object"]);
//				if (!isset($this->isNativeSSH) || (isset($this->isNativeSSH) && $this->isNativeSSH != true)) {
//				}
				echo $this->doSSHCommand($server["ssh2Object"],
					'echo "Pharaoh Remote SSH on ...' . $server["target"] . '"', true); } }
		return true;
	}

    protected function attemptSSH2Connection($server) {
        $pword = (isset($server["pword"])) ? $server["pword"] : false;
        $pword = (isset($server["password"])) ? $server["password"] : $pword;
        $invokeFactory = new \Model\Invoke() ;
        $serverObj = $invokeFactory->getModel($this->params, "Server") ;
        $serverObj->init($server['target'], $server['user'], $pword, isset($server['port']) ? $server['port'] : 22);
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

    private function findDriver() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $optionsKeep = array("os" => "DriverBashSSH", "native" => "DriverNativeSSH", "seclib" => "DriverSecLib") ;
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

	private function askForSSHShellExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true;  }
		$question = 'Invoke SSH Shell on Server group?';
		return self::askYesOrNo($question);
	}

	private function askForSSHScriptExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true; }
		$question = 'Invoke SSH Script on Server group?';
		return self::askYesOrNo($question);
	}

	private function askForSSHDataExecute() {
		if (isset($this->params["yes"]) && $this->params["yes"] == true) {
			return true;}
		$question = 'Invoke SSH Data on Server group?';
		return self::askYesOrNo($question);
	}

	private function askForScriptLocation() {
		if (isset($this->params["ssh-script"])) {
			return $this->params["ssh-script"]; }
		$question = 'Enter Location of bash script to execute';
		return self::askForInput($question, true);
	}

	private function askForSSHData() {
		if (isset($this->params["ssh-data"])) {
			return $this->params["ssh-data"]; }
		$question = 'Enter data to execute via SSH';
		return self::askForInput($question, true);
	}

	private function askForServerInfo() {
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
			if (count($this->servers) < 1) {
				$question .= "You need to enter at least one server\n"; }
			$serverAddingExecution = self::askYesOrNo($question);
		}
	}

	private function askForTimeout() {
		if (isset($this->params["timeout"])) {
			return; }
		if (isset($this->params["guess"])) {
			$this->params["timeout"] = 100;
			return; }
		$question = 'Please Enter SSH Timeout in seconds';
		$input = self::askForInput($question, true);
		$this->params["timeout"] = $input;
	}

	private function askForPort() {
		if (isset($this->params["port"])) {
			return; }
		if (isset($this->params["guess"])) {
			$this->params["port"] = 22;
			return; }
		$question = 'Please Enter remote SSH Port';
		$input = self::askForInput($question, true);
		$this->params["port"] = $input;
	}

	private function askForServerTarget() {
		if (isset($this->params["ssh-target"])) {
			return $this->params["ssh-target"];	}
		$question = 'Please Enter SSH Server Target Host Name/IP';
		$input = self::askForInput($question, true);

		return $input;
	}

	private function askForServerUser() {
		if (isset($this->params["ssh-user"])) {
			return $this->params["ssh-user"]; }
		$question = 'Please Enter SSH User';
		$input = self::askForInput($question, true);
		return $input;
	}

	private function askForServerPassword()	{
		if (isset($this->params["ssh-key-path"])) {
			return $this->params["ssh-key-path"]; }
        else {
			if (isset($this->params["ssh-pass"])) {
				return $this->params["ssh-pass"]; } }
		$question = 'Please Enter Server Password or Key Path';
		$input = self::askForInput($question);
		return $input;
	}

	private function askForACommand() {
		$question = 'Enter command to be executed on remote servers? Enter none to close connection and end program';
		$input = self::askForInput($question);
		return ($input == "") ? false : $input;
	}

	private function changeBashPromptToPharaoh($sshObject) {
		$command = 'echo "export PS1=PHARAOHPROMPT" > ~/.bash_login ';
		return $sshObject->exec("$command\n");
	}

	private function doSSHCommand($sshObject, $command, $first = null) {
		return $sshObject->exec($command);
	}

}