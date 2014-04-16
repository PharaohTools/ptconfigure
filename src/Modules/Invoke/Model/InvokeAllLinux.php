<?php

Namespace Model;

class InvokeAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $servers = array();
    private $sshCommands;

    public function askWhetherToInvokeSSHShell() {
        return $this->performInvokeSSHShell();
    }

    public function askWhetherToInvokeSSHScript($params=null) {
        return $this->performInvokeSSHScript($params);
    }

    public function performInvokeSSHShell() {
        if ($this->askForSSHShellExecute() != true) { return false; }
        $this->populateServers();
        $commandExecution = true;
        echo "Opening CLI...\n"  ;
        while ($commandExecution == true) {
            $command = $this->askForACommand();
            if ( $command == false) {
                $commandExecution = false; }
            else {
                foreach ($this->servers as &$server) {
                    echo "[".$server["target"]."] Executing $command...\n"  ;
                    echo $this->doSSHCommand($server["ssh2Object"], $command) ;
                    echo "[".$server["target"]."] $command Completed...\n"  ; } } }
        echo "Shell Completed";
        return true;
    }

    public function performInvokeSSHScript(){
        if ($this->askForSSHScriptExecute() != true) { return false; }
        $scriptLoc = $this->askForScriptLocation();
        $this->populateServers();
        $this->sshCommands = explode("\n", file_get_contents($scriptLoc) ) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand ) ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
        echo "Script by SSH Completed";
        return true;
    }

    public function performInvokeSSHData(){
        $data = $this->askForSSHData();
        $this->populateServers();
        $this->sshCommands = explode("\n", $data) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand ) ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
        echo "Data by SSH Completed";
        return true;
    }

    public function populateServers() {
        $this->loadServerData();
        $this->loadSSHConnections();
    }

    private function loadServerData() {
        $srvAvail = (isset($autoPilot["sshInvokeServers"]) && is_array($autoPilot["sshInvokeServers"]) &&
            count($autoPilot["sshInvokeServers"]) > 0);
        $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
        if ($srvAvail == true) {
            $this->servers = $autoPilot["sshInvokeServers"]; }
        else if (count($allProjectEnvs) > 0) {
            $question = 'Use Environments Configured in Project?';
            $useProjEnvs = self::askYesOrNo($question, true);
            if ($useProjEnvs == true ) {
                $this->servers = new \ArrayObject($allProjectEnvs) ;
                return; } }
        else {
            $this->askForServerInfo(); }
    }

    private function loadSSHConnections() {
        echo 'Attempting to load SSH connections... ';
        foreach ($this->servers as &$server) {
            $attempt = $this->attemptSSH2Connection($server) ;
            if ($attempt == null) {
                echo 'Connection to Server '.$server["target"].' failed. '; }
            else {
                $server["ssh2Object"] = $attempt ;
                echo $this->changeBashPromptToPharoah($server["ssh2Object"]);
                echo $this->doSSHCommand($server["ssh2Object"], 'echo "Cleopatra Remote SSH on ...'.$server["target"].'"', true ) ; } }
        return true;
    }

    private function attemptSSH2Connection($server) {
        $srcFolder =  str_replace("/Model", "", dirname(__FILE__) ) ;
        $ssh2File = $srcFolder."/Libraries/seclib/Net/SSH2.php" ;
        require_once($ssh2File) ;
        $ssh = new \Net_SSH2($server["target"]);
        if ($ssh->login($server["user"], $server["pword"]) == true) {
            return $ssh; }
        return null;
    }

    private function askForSSHShellExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Invoke SSH Shell on Server group?';
        return self::askYesOrNo($question);
    }

    private function askForSSHScriptExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Invoke SSH Script on Server group?';
        return self::askYesOrNo($question);
    }

    private function askForSSHDataExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Invoke SSH Data on Server group?';
        return self::askYesOrNo($question);
    }

    private function askForScriptLocation(){
        if (isset($this->params["ssh-script"])) { return $this->params["ssh-script"] ; }
        $question = 'Enter Location of bash script to execute';
        return self::askForInput($question, true);
    }

    private function askForSSHData(){
        if (isset($this->params["ssh-data"])) { return $this->params["ssh-data"] ; }
        $question = 'Enter data to execute via SSH';
        return self::askForInput($question, true);
    }

    private function askForServerInfo(){
        $startQuestion = <<<QUESTION
***********************************
*   Due to a software limitation, *
*   The user that you user here   *
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

    private function askForServerTarget(){
        if (isset($this->params["ssh-target"])) { return $this->params["ssh-target"] ; }
        $question = 'Please Enter SSH Server Target Host Name/IP';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    private function askForServerUser(){
        if (isset($this->params["ssh-user"])) { return $this->params["ssh-user"] ; }
        $question = 'Please Enter SSH User';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    private function askForServerPassword(){
        if (isset($this->params["ssh-key-path"])) { return $this->params["ssh-key-path"] ; }
        else if (isset($this->params["ssh-pass"])) { return $this->params["ssh-pass"] ; }
        $question = 'Please Enter Server Password or Key Path';
        $input = self::askForInput($question) ;
        return  $input ;
    }

    private function askForACommand(){
        $question = 'Enter command to be executed on remote servers? Enter none to close connection and end program';
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function changeBashPromptToPharoah( $sshObject ) {
        $command = 'echo "export PS1=PHAROAHPROMPT" > ~/.bash_login ' ;
        return $sshObject->exec("$command\n") ;
    }

    private function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("PHAROAHPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("PHAROAHPROMPT") ;
        return str_replace("PHAROAHPROMPT", "", $returnVar) ;
    }

}