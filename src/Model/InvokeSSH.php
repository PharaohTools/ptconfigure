<?php

Namespace Model;

class InvokeSSH extends Base {

    private $servers = array();
    private $sshCommands;

    public function askWhetherToInvokeSSHShell() {
        return $this->performInvokeSSHShell();
    }

    public function askWhetherToInvokeSSHScript($params=null) {
        return $this->performInvokeSSHScript($params);
    }

    public function runAutoPilotInvokeSSHData($autoPilot) {
        if ( $autoPilot->sshInvokeSSHDataExecute !== true ) { return false; }
        $this->populateServers($autoPilot);
        $this->sshCommands = explode("\n", $autoPilot->sshInvokeSSHDataData ) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand )."\n" ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
        return true;
    }

    public function runAutoPilotInvokeSSHScript($autoPilot) {
        if ( $autoPilot->sshInvokeSSHScriptExecute !== true ) { return false; }
        $this->populateServers($autoPilot);
        $this->sshCommands = explode("\n", file_get_contents($autoPilot->sshInvokeSSHScriptFile) ) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand )."\n" ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
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

    public function performInvokeSSHScript($params=null){
        if ($this->askForSSHScriptExecute() != true) { return false; }
        if ($params==null) { $params = array();
                             $params[0] = $this->askForScriptLocation(); }
        $this->populateServers();
        $this->sshCommands = explode("\n", file_get_contents($params[0]) ) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand ) ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
        echo "Script Completed";
        return true;
    }

    public function populateServers($autoPilot=null) {
        $this->loadServerData($autoPilot);
        $this->loadSSHConnections();
    }

    private function loadServerData($autoPilot=null) {
        $srvAvail = (isset($autoPilot->sshInvokeServers) && is_array($autoPilot->sshInvokeServers) &&
            count($autoPilot->sshInvokeServers) > 0);
        if ($srvAvail == true) {
            $this->servers = $autoPilot->sshInvokeServers; }
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
                echo $this->changeBashPromptToDevhelper($server["ssh2Object"]);
                echo $this->doSSHCommand($server["ssh2Object"], 'echo "Devhelper Remote SSH on ...'.$server["target"].'"', true ) ; } }
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
        $question = 'Invoke SSH Shell on Server group?';
        return self::askYesOrNo($question);
    }

    private function askForSSHScriptExecute(){
        $question = 'Invoke SSH Script on Server group?';
        return self::askYesOrNo($question);
    }

    private function askForScriptLocation(){
        $question = 'Enter Location of bash script to execute';
        return self::askForInput($question, true);
    }

    private function askForServerInfo(){
        $startQuestion = <<<QUESTION
***********************************
*   Due to a software limitation, *
*   The user that you user here   *
*  will have their command prompt *
*   changed to DEVHELPERPROMPT... *
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
        $question = 'Please Enter Server Target Host Name/IP';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    private function askForServerUser(){
        $question = 'Please Enter Server User';
        $input = self::askForInput($question, true) ;
        return  $input ;
    }

    private function askForServerPassword(){
        $question = 'Please Enter Server Password';
        $input = self::askForInput($question) ;
        return  $input ;
    }

    private function askForACommand(){
        $question = 'Enter command to be executed on remote servers? Enter none to close connection and end program';
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function changeBashPromptToDevhelper( $sshObject ) {
        $command = 'echo "export PS1=DEVHELPERPROMPT" >> ~/.bashrc ' ;
        return $sshObject->exec("$command\n") ;
    }

    private function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("DEVHELPERPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("DEVHELPERPROMPT") ;
        return str_replace("DEVHELPERPROMPT", "", $returnVar) ;
    }

}