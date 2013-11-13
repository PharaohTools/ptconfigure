<?php

Namespace Model;

class InvokeSSH extends Base {

    private $servers = array();
    private $sshCommands;

    public function runAutoPilot($autoPilot) {
        $this->runAutoPilotInvokeSSHData($autoPilot);
        $this->runAutoPilotInvokeSSHScript($autoPilot);
        return true;
    }

    public function askWhetherToInvokeSSHShell() {
        return $this->performInvokeSSHShell();
    }

    public function askWhetherToInvokeSSHScript($params=null) {
        return $this->performInvokeSSHScript($params);
    }

    public function runAutoPilotInvokeSSHData($autoPilot) {
        if ( !isset($autoPilot["sshInvokeSSHDataExecute"]) || $autoPilot["sshInvokeSSHDataExecute"] !== true ) {
            return false; }
        $this->populateServers($autoPilot);
        $this->sshCommands = explode("\n", $autoPilot["sshInvokeSSHDataData"] ) ;
        foreach ($this->sshCommands as $sshCommand) {
            foreach ($this->servers as &$server) {
                echo "[".$server["target"]."] Executing $sshCommand...\n"  ;
                echo $this->doSSHCommand($server["ssh2Object"], $sshCommand )."\n" ;
                echo "[".$server["target"]."] $sshCommand Completed...\n"  ; } }
        return true;
    }

    public function runAutoPilotInvokeSSHScript($autoPilot) {
        if ( !isset($autoPilot["sshInvokeSSHScriptExecute"]) || $autoPilot["sshInvokeSSHScriptExecute"] !== true ) {
            return false; }
        $this->populateServers($autoPilot);
        $this->sshCommands = explode("\n", file_get_contents($autoPilot["sshInvokeSSHScriptFile"]) ) ;
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

//
//    public function performInvokeSSHShell() {
//        if ($this->askForSSHShellExecute() != true) { return false; }
//        $this->populateServers();
//        $commandExecution = true;
//        $serializedSshArray = $this->getSerializedSshArray() ;
//        echo "Opening CLI...\n"  ;
//        while ($commandExecution == true) {
//            $command = $this->askForACommand();
//            if ( $command == false) {
//                $commandExecution = false; }
//            else {
//                $this->executeOneCommandInput($this->servers, $command); } }
//        echo "Shell Completed";
//        return true;
//    }
//
//    private function getSerializedSshArray(){
//        $serializedSshArray = array();
//        foreach ($this->servers as &$server) {
//            $serialized = serialize($server) ;
//            file_put_contents("/tmp/serialized{$server["target"]}", $serialized) ;
//            $serializedSshArray[] = "/tmp/serialized{$server["target"]}" ; }
//        return $serializedSshArray ;
//    }
//
//    private function executeOneCommandInput($servers, $command) {
//        $allPlxOuts = array();
//        $tempScript = $this->makeCommandFile($command);
//        foreach ($servers as $server) {
//            $outfile = $this->getFileToWrite("final");
//            $cmd = 'dapperstrano invoke script execute --ssh-script="sh ' . $tempScript . '" --output-file="'
//                . $outfile .'" --ssh-user="'.$server["user"].'" --ssh-pword="'.$server["pword"]
//                .'" --ssh-target="'.$server["target"] . '" > /dev/null &';
//            echo $cmd ;
//            system($cmd, $plxExit);
//            $allPlxOuts[] = array($tempScript, $outfile); }
//        $copyPlxOuts = $allPlxOuts;
//        $fileData = "";
//        $ignores = array();
//        sleep(3);
//
//        $commandResults = array();
//        while (count($commandResults) < count($allPlxOuts)) {
//            for ($i=0; $i<count($copyPlxOuts); $i++) {
//                if (in_array($i, $ignores)) {
//                    continue; }
//                $fileToScan = $copyPlxOuts[$i][1];
//                $file = new \SplFileObject($fileToScan);
//                $file->seek(1);
//                $completionStatus = substr($file->current(), 10, 1);
//                if ($completionStatus=="1") {
//                    $file->seek(0);
//                    echo "Completed task: ".substr($file->current(), 9);
//                    $file->seek(2);
//                    $exitStatus = substr($file->current(), 13, 1);
//                    $commandResults[] = $exitStatus;
//                    $fileData .= file_get_contents($fileToScan);
//                    $ignores[] = $i; } }
//            echo ".";
//            sleep(3); }
//        $anyFailures = in_array("1", $commandResults);
//        return array ($fileData, $anyFailures);
//    }

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
        $srvAvail = (isset($autoPilot["sshInvokeServers"]) && is_array($autoPilot["sshInvokeServers"]) &&
            count($autoPilot["sshInvokeServers"]) > 0);
        if ($srvAvail == true) {
            $this->servers = $autoPilot["sshInvokeServers"]; }
        else if ( isset($this->params["ssh-script"]) && isset($this->params["ssh-user"]) &&
                isset($this->params["ssh-pword"]) && isset($this->params["ssh-target"]) ) {
            $this->servers = array();
            $this->servers[] = array("target" => $this->params["ssh-target"], "user" => $this->params["ssh-user"],
                "pword" => $this->params["ssh-pword"]); }
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
                echo $this->changeBashPromptToDapperstrano($server["ssh2Object"]);
                echo $this->doSSHCommand($server["ssh2Object"], 'echo "Dapperstrano Remote SSH on ...'.$server["target"].'"', true ) ; } }
        return true;
    }

    private function attemptSSH2Connection($server) {
        $srcFolder =  str_replace("/Model", "", dirname(__FILE__) ) ;
        $ssh2File = $srcFolder."/Libraries/seclib/Net/SSH2.php" ;
        require_once($ssh2File) ;
        $ssh = new \Net_SSH2($server["target"]);
        // if pword starts with a / and is an existing file we assume it is a path and load a keyfile
        if (substr($server["pword"], 0, 1)=="/" && file_exists($server["pword"])) {
            $srcFolder =  str_replace("/Model", "", dirname(__FILE__) ) ;
            $cryptRsaFile = $srcFolder."/Libraries/seclib/Crypt/RSA.php" ;
            require_once($cryptRsaFile) ;
            $keyObject = new \Crypt_RSA();
            $keyObject->loadKey( file_get_contents($server["pword"]), CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
            // this uses openssh style key @todo should also check for an ssh2 key if this fails
            $didItLogin = $ssh->login($server["user"], $keyObject);
            return ($didItLogin==true) ? $ssh : null ; }
        else if ($ssh->login($server["user"], $server["pword"]) == true) {
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
*  changed to DAPPERSTRANOPROMPT  *
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

    private function changeBashPromptToDapperstrano( $sshObject ) {
        $command = 'echo "export PS1=DAPPERSTRANOPROMPT" > ~/.bashrc ' ;
        $a = $sshObject->exec("$command\n") ;
        $command = 'echo "export PS1=DAPPERSTRANOPROMPT" > ~/.bash_login ' ;
        $b = $sshObject->exec("$command\n") ;
        return $a . $b ;
    }

    private function doSSHCommand( $sshObject, $command, $first=null ) {
        $returnVar = ($first==null) ? "" : $sshObject->read("DAPPERSTRANOPROMPT") ;
        $sshObject->write("$command\n") ;
        $returnVar .= $sshObject->read("DAPPERSTRANOPROMPT") ;
        return str_replace("DAPPERSTRANOPROMPT", "", $returnVar) ;
    }

    /*@todo remove these two functions if they dont get used in invoke, they are from parallax*/
    private function makeCommandFile($command) {
        $random = $this->baseTempDir.DIRECTORY_SEPARATOR.mt_rand(100, 99999999999);
        file_put_contents($random.'-dapper-invoke-temp.sh', $command);
        return $random.'-dapper-invoke-temp.sh';
    }

    private function getFileToWrite($file_type) {
        $random = $this->baseTempDir.DIRECTORY_SEPARATOR.mt_rand(100, 99999999999);
        if ($file_type == "temp") { return $random.'temp.txt'; }
        if ($file_type == "final") { return $random.'final.txt'; }
        else { return null ; }
    }

}