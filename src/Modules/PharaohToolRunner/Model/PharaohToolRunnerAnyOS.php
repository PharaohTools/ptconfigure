<?php

Namespace Model;

class PharaohToolRunnerAnyOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToPharaohToolRunnerPut() {
        return $this->performPharaohToolRunnerPut();
    }

    public function performPharaohToolRunnerPut() {
        if ($this->askForPharaohToolRunnerExecute() != true) {
            return false ;
        }
        $tool = $this->getNameOfToolToRun() ;
        $tool = $this->parseAvailableTools($tool);
        $module = $this->getNameOfModuleToRun() ;
        $action = $this->getNameOfActionToRun() ;
        $prefix = $this->getForcePrefix() ;
        $suffix = $this->getForceSuffix() ;
        return $this->doPharaohToolRun($tool, $module, $action, $prefix, $suffix) ;
    }

    protected function doPharaohToolRun($tool, $module, $action, $prefix  = false, $suffix  = false) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("About to spawn execution of a Pharaoh Tool", $this->getModuleName());
        $env = $this->getEnvironmentName() ;
        $askpass = $this->askForServerPassword(true) ;
        if ($env !== false && strlen($env)>0) {
            $logging->log("Environment specified, initiating a remote execution", $this->getModuleName());
            $sshParams["yes"] = true ;
            $sshParams["guess"] = true ;
            $logging->log("Target Environment name {$env} specified to execute Pharaoh command in", $this->getModuleName());
            $sshParams["environment-name"] = $env ;
            $env_scope = $this->getEnvironmentScope() ;
            $logging->log("Target Environment scope {$env_scope} specified to target machines", $this->getModuleName());
            if ($askpass !== false) {
                $sshParams["pass"] = $askpass ; }
            $sshParams["env-scope"] = $env_scope ;
            $sshParams["driver"] = (isset($this->params["driver"])) ? $this->params["driver"] : "seclib" ;
            $sshParams["port"] = (isset($this->params["port"])) ? $this->params["port"] : 22 ;
            $sshParams["timeout"] = (isset($this->params["timeout"])) ? $this->params["timeout"] : 30 ;
            $sftpParams = $sshParams ;
            $hopEnv = $this->getHopEnvironmentName() ;
            if ($hopEnv !== false) {
                $logging->log("Hop environment specified, will connect to target environment {$env} through hop environment {$hopEnv}", $this->getModuleName());
                $sshParams["hops"] = $hopEnv ;
                $env_scope = $this->getHopEnvironmentScope() ;
                $sshParams["hop-env-scope"] = $env_scope ;}
            $afn = $this->getAutopilotFileName() ;
            $file_only = basename($afn) ;

            if (isset($this->params["remote-tmp-dir"])) {
                $remote_tmp_dir = $this->params["remote-tmp-dir"] ;
                $remote_tmp_dir = $this->ensureTrailingSlash($remote_tmp_dir) ;
            } else {
                $remote_tmp_dir = $this->ensureTrailingSlash(self::$tempDir) ;
            }

            $target_path = $remote_tmp_dir.$file_only ;
            if (
//                isset($hopEnv) &&
//                strlen($hopEnv)>0 &&
                isset($afn) &&
                strlen($afn)>0 ) {
                $logging->log("Autopilot has been specified, Setting SFTP details to push Autopilot file to Target", $this->getModuleName());
                $sftpParams["source"] = $afn ;
                $sftpParams["target"] = $target_path ;
                $sftpParams["environment-name"] = $env ;
                $sftpParams["env-scope"]= $this->getEnvironmentScope() ;
                if ($askpass !== false) {
                    $sftpParams["pass"] = $askpass ; }
                if ($hopEnv !== false) {
                    $logging->log("Hop environment specified, will connect to target environment through hop environment {$hopEnv}", $this->getModuleName());
                    $sftpParams["hops"] = $hopEnv ;
                    $sftpParams["hop-env-scope"]= $this->getHopEnvironmentScope() ;}
                $sftpFactory = new \Model\SFTP() ;
                $sftp = $sftpFactory->getModel($sftpParams ,"Default") ;
                $logging->log("About to SFTP push local Autopilot file $afn to {$target_path} in Environment {$env}", $this->getModuleName());
                $res = $sftp->performSFTPPut() ;
                if ($res == false) {
                    $logging->log("Failed transfer of local Autopilot file $afn to {$target_path} in Environment {$env}", $this->getModuleName());
                    return false ; }
                else {
                    $logging->log("Successful transfer of local Autopilot file $afn to {$target_path} in Environment {$env}", $this->getModuleName()); } }
            $this->params["autopilot-file"] = $target_path ;
//            $sshParams["ssh-data"] = "ptconfigure || bash <(wget -qO- http://www.pharaohtools.com/linux.bash)) " ;
            $sshFactory = new \Model\Invoke() ;
//          $ssh = $sshFactory->getModel($sshParams ,"Default") ;
//          $res = $ssh->askWhetherToInvokeSSHData() ;
//          if ($res == false) { return false ; }
            $param_string = $this->getParametersToForward() ;
            $comm = "$tool $module $action $param_string" ;
//            var_dump('com com', $comm) ;
            if ($prefix != false) {
                $logging->log("Prefixing command with {$prefix}", $this->getModuleName());
                $comm = $prefix." ".$comm ;
            }
            if ($suffix != false) {
                $logging->log("Suffixing command with {$suffix}", $this->getModuleName());
                $comm = $comm.' '.$suffix ;
            }
            $logging->log("Pharaoh Tool Runner creating command $comm for remote execution on Environment {$env}", $this->getModuleName());
            $sshParams["ssh-data"] = "$comm" ;
            $ssh = $sshFactory->getModel($sshParams ,"Default") ;
            $res = $ssh->askWhetherToInvokeSSHData() ;
            return ($res == true) ? true : false ; }
        else {
            $logging->log("No environment name specified, executing command locally", $this->getModuleName());
            $param_string = $this->getParametersToForward() ;
            $comm = "$tool $module $action $param_string" ;
            if ($prefix != false) {
                $logging->log("Prefixing command with {$prefix}", $this->getModuleName());
                $comm = $prefix." ".$comm ;
            }
            if ($suffix != false) {
                $logging->log("Suffixing command with {$suffix}", $this->getModuleName());
                $comm = $comm.' '.$suffix ;
            }
            $logging->log("Pharaoh Tool Runner creating and executing command $comm", $this->getModuleName());
//            $logging->log("Executing $comm", $this->getModuleName());
//            self::executeAndOutput($comm) ;
//            return true ;
//            $rc = self::executeAndGetReturnCode($comm, true, false) ;
//            passthru($comm, $rc) ;
            $rc = $this->liveOutput($comm) ;
            $wasOk = ($rc==0) ;
//            $wasOk = ($rc['rc']==0) ;
            if ($wasOk == true) { return true ; }
            else {
                $logging->log("Pharaoh Tool Runner received a non-zero exit code of {$rc["rc"]}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                return false ; } }
    }

    protected function liveOutput($comm) {
        require_once(dirname(__DIR__).DS.'Libraries'.DS.'vendor'.DS.'autoload.php') ;
        $process = new \Symfony\Component\Process\Process($comm);
        $process->setTimeout(0);
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo $data;
            } else { // $process::ERR === $type
                echo "ERR: ".$data;
            }
        }
        return $process->getExitCode();
    }

    protected function askForPharaohToolRunnerExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Run command in a Pharaoh Tool?';
        return self::askYesOrNo($question);
    }

    protected function getNameOfToolToRun() {
        if (isset($this->params["tool"])) { return $this->params["tool"] ; }
        else { $question = "Enter tool name"; }
        $input = self::askForInput($question) ;
        return ($input =="") ? false : $input ;
    }

    protected function getHopEnvironmentName() {
        if (isset($this->params["hops"])) { return $this->params["hops"] ; }
        return false ;
    }

    protected function getAutopilotFileName() {
        if (isset($this->params["params"])) {
            $temp_params = $this->transformOurParamsToArray($this->params["params"]) ;
        if (isset($temp_params["autopilot-file"])) { return $temp_params["autopilot-file"] ; }
        else if (isset($temp_params["af"])) {
            $temp_params["autopilot-file"] = $temp_params["af"] ;
            return $temp_params["autopilot-file"] ; } }
        else {
            return false; }
        return false ;
    }

    protected function getEnvironmentName(){
        if (isset($this->params["environment-name"])) { return $this->params["environment-name"] ; }
        if (isset($this->params["env"])) { return $this->params["env"] ; }
        if (isset($this->params["guess"])) {
            $this->params["environment-name"] = "" ;
            return $this->params["environment-name"] ; }
        $question = "Enter Environment name, none to run locally";
        $this->params["environment-name"] = self::askForInput($question) ;
        return $this->params["environment-name"] ;
    }

    protected function getEnvironmentScope(){
        if (isset($this->params["environment-scope"])) { return $this->params["environment-scope"] ; }
        if (isset($this->params["env-scope"])) { return $this->params["env-scope"] ; }
        if (isset($this->params["guess"])) {
            $this->params["environment-scope"] = "public" ;
            return $this->params["environment-scope"] ; }
        $question = "Enter Scope, public or private";
        $this->params["environment-scope"] = self::askForInput($question) ;
        return $this->params["environment-scope"] ;
    }

    protected function getHopEnvironmentScope(){
        if (isset($this->params["hop-environment-scope"])) { return $this->params["hop-environment-scope"] ; }
        if (isset($this->params["hop-env-scope"])) { return $this->params["hop-env-scope"] ; }
        if (isset($this->params["guess"])) {
            $this->params["hop-environment-scope"] = "public" ;
            return $this->params["hop-environment-scope"] ; }
        $question = "Enter Scope, public or private";
        $this->params["hop-environment-scope"] = self::askForInput($question) ;
        return $this->params["hop-environment-scope"] ;
    }

    protected function parseAvailableTools($tool){
        $all_tool_synonyms = array();
        $all_tool_synonyms["ptconfigure-enterprise"] = array("ptconfigure-ent", "configure-ent", "configent", "ptcent", "ptconfigure-ent.cmd") ;
        $all_tool_synonyms["ptconfigure"] = array("ptconfigure", "configure", "config", "ptc", "ptconfigure.cmd") ;
        $all_tool_synonyms["ptbuild"] = array("ptbuild", "build", "ptb", "ptbuild.cmd") ;
        $all_tool_synonyms["ptdeploy"] = array("ptdeploy", "deploy", "ptd", "ptdeploy.cmd") ;
        $all_tool_synonyms["ptvirtualize"] = array("ptvirtualize", "virtualize", "ptv", "develop", "virtualize.cmd") ;
        $all_tool_synonyms["pttrack"] = array("pttrack", "track", "pttr", "pttrack.cmd") ;
        $all_tool_synonyms["pttest"] = array("pttest", "test", "ptte", "pttest.cmd") ;
        foreach ($all_tool_synonyms as $final_tool => $cur_synonyms) {
            if (in_array($tool, $cur_synonyms)) {
                $tool = $final_tool ; } }
        return $tool ;
    }

    protected function getNameOfModuleToRun() {
        if (isset($this->params["module"])) {
            return $this->params["module"] ;
        }
        else { $question = "Enter module name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getNameOfActionToRun() {
        if (isset($this->params["action"])) {
            return $this->params["action"] ; }
        else {
            $question = "Enter action name";
            $input = self::askForInput($question) ;
            return ($input=="") ? false : $input ;
        }
    }

    protected function getForcePrefix() {
        if (isset($this->params["prefix"])) {
            return $this->params["prefix"] ;
        } else if (isset($this->params["guess"])) {
            return false ;
        } else {
            $question = "Add prefix to command on Target?";
            $input = self::askYesOrNo($question) ;
            return ($input== true) ? true : false ;
        }
    }


    protected function getForceSuffix() {
        if (isset($this->params["suffix"])) {
            return $this->params["suffix"] ;
        } else if (isset($this->params["guess"])) {
            return false ;
        } else {
            $question = "Add suffix to command on Target?";
            $input = self::askYesOrNo($question) ;
            return ($input== true) ? true : false ;
        }
    }

    protected function getParametersToForward(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["params"])) {
            $afn = $this->getAutopilotFileName() ;
            $env = $this->getEnvironmentName() ;
            if ($afn !== false && $env !== false && strlen($env)>0) {
                $res = $this->transformOurParams($this->params["params"], array("af", "autopilot-file") ) ;
                $file_only = basename($afn) ;
                if (isset($this->params["remote-tmp-dir"])) {
                    $remote_tmp_dir = $this->params["remote-tmp-dir"] ;
                    $remote_tmp_dir = $this->ensureTrailingSlash($remote_tmp_dir) ;
                } else {
                    $remote_tmp_dir = $this->ensureTrailingSlash(self::$tempDir) ;
                }
                $target_path = $remote_tmp_dir.$file_only ;
                $logging->log("Automatically forwarding autopilot file parameter value of {$target_path}", $this->getModuleName());
                $res .= " --autopilot-file=".$target_path ; }
            else {
                $res = $this->transformOurParams($this->params["params"]) ; }
            return $res ;
        } else if (isset( $this->params["guess"]) && $this->params["guess"]==true) {
            $res = "" ;
        } else {
            $question = "Enter parameter string" ;
            $input = self::askForInput($question) ;
            $res = $this->transformOurParams($input) ; }
        return $res ;
    }

    protected function transformOurParams($pstr, $drop_keys = array(), $to_array = false) {
        if (!is_array($pstr)) {
            $pairs = explode(",", $pstr) ;
        } else {
            $pairs = $pstr ;
        }
        $parameter_string = "" ;
//        var_dump($pairs) ;
//        die() ;
        foreach ($pairs as $key => $pair) {
            if (is_array($pair)) {
                if (!in_array($key, $drop_keys)) {
                    $val = serialize($pair) ;
                    $parameter_string .= " --{$key}='".$val."'" ; }
            } else if (is_string($key)) {
                if (!in_array($key, $drop_keys)) {
                    $parameter_string .= " --{$key}=\"{$pair}\"" ; }
            } else if (strpos($pair, ":") !== false) {
                $key = substr($pair, 0, strpos($pair, ":") ) ;
                $val = substr($pair, strpos($pair, ":") + 1 ) ;
                $val = $this->transformParameterValue($val) ;
                if (!in_array($key, $drop_keys)) {
                    $parameter_string .= " --{$key}=\"{$val}\"" ; }
            } else {
//                var_dump('transformOurParams', $key, $pair, $pairs) ;
//                die() ;
                if (!in_array($pair, $drop_keys)) {
                    $parameter_string .= " --\"{$pair}\"" ; } } }
        return $parameter_string ;
    }

    protected function transformOurParamsToArray($pstr, $drop_keys = array()) {
        if (is_array($pstr)) {
            return $pstr ;
        }
        $pairs = explode(",", $pstr) ;
        $ray = array() ;
        foreach ($pairs as $pair) {
            if (strpos($pair, ":") !== false) {
                $key = substr($pair, 0, strpos($pair, ":") ) ;
                $val = substr($pair, strpos($pair, ":") + 1 ) ;
                if (!in_array($key, $drop_keys)) { $ray[$key] = $val ; } }
            else {
                if (!in_array($pair, $drop_keys)) { $ray[$pair] = "true" ;} } }
        return $ray ;
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
            return $input; }
        return false ;
    }

}