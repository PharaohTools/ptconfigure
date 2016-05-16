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
        if ($this->askForPharaohToolRunnerExecute() != true) { return false; }
        $tool = $this->getNameOfToolToRun() ;
        $tool = $this->parseAvailableTools($tool);
        $module = $this->getNameOfModuleToRun() ;
        $action = $this->getNameOfActionToRun() ;
        return $this->doPharaohToolRun($tool, $module, $action) ;
    }

    protected function doPharaohToolRun($tool, $module, $action) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $env = $this->getEnvironmentName() ;
        if ($env !== false) {
            $logging->log("Environment name {$env} specified to execute Pharaoh command in", $this->getModuleName());
            $sshParams["yes"] = true ;
            $sshParams["guess"] = true ;
            $sshParams["environment-name"] = $env ;
            $sshParams["driver"] = "seclib" ;
            $sshParams["port"] = (isset($papyrus["port"])) ? $papyrus["port"] : 22 ;
            $sshParams["timeout"] = (isset($papyrus["timeout"])) ? $papyrus["timeout"] : 30 ;
            $sftpParams = $sshParams ;
            $hopEnv = $this->getHopEnvironmentName() ;
            if ($hopEnv !== false) {
                $sshParams["hops"] = $this->$hopEnv() ;}
            var_dump("p: ", $this->params) ;
            $afn = $this->getRunAutopilotFileName() ;
            var_dump("afn: ", $afn) ;
            $file_only = basename($afn) ;
            var_dump("fo: ", $file_only) ;
            $target_path = '/tmp/'.$file_only ;
            if (
//                isset($hopEnv) &&
//                strlen($hopEnv)>0 &&
                isset($afn) &&
                strlen($afn)>0 ) {
                $logging->log("Setting SFTP details to push Autopilot file to Target", $this->getModuleName());
                $sftpParams["source"] = $afn ;
                $sftpParams["target"] = $target_path ;
                $sftpParams["environment-name"] = $env ;
                $sftpFactory = new \Model\SFTP() ;
                $sftp = $sftpFactory->getModel($sftpParams ,"Default") ;
                $res = $sftp->performSFTPPut() ;
                if ($res == false) { return false ; } }
            $this->params["autopilot-file"] = $target_path ;
//            $sshParams["ssh-data"] = "ptconfigure || bash <(wget -qO- http://www.pharaohtools.com/linux.bash)) " ;
            $sshFactory = new \Model\Invoke() ;
//            $ssh = $sshFactory->getModel($sshParams ,"Default") ;
//            $res = $ssh->askWhetherToInvokeSSHData() ;
//            if ($res == false) { return false ; }
            $param_string = $this->getParametersToForward() ;
            $comm = "$tool $module $action $param_string" ;
            $logging->log("Pharaoh Tool Runner creating command $comm", $this->getModuleName());
            $sshParams["ssh-data"] = "$comm" ;
            $ssh = $sshFactory->getModel($sshParams ,"Default") ;
            $res = $ssh->askWhetherToInvokeSSHData() ;
            return ($res == true) ? true : false ; }
        else {
            $logging->log("No environment name specified, executing command locally", $this->getModuleName());
            $param_string = $this->getParametersToForward() ;
            $comm = "$tool $module $action $param_string" ;
            $logging->log("Pharaoh Tool Runner creating command $comm", $this->getModuleName());
            $logging->log("Executing $comm", $this->getModuleName());
            $rc = self::executeAndGetReturnCode($comm, true, false) ;
            return ($rc["rc"]==0) ? true : false ; }

    }

    protected function askForPharaohToolRunnerExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Run command in a Pharaoh Tool?';
        return self::askYesOrNo($question);
    }

    protected function getNameOfToolToRun(){
        if (isset($this->params["tool"])) { return $this->params["tool"] ; }
        else { $question = "Enter tool name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getHopEnvironmentName(){
        if (isset($this->params["hops"])) { return $this->params["hops"] ; }
        return false ;
    }

    protected function getRunAutopilotFileName(){
        if (isset($this->params["runner-autopilot-file"])) { return $this->params["runner-autopilot-file"] ; }
        else if (isset($this->params["raf"])) {
            $this->params["runner-autopilot-file"] = $this->params["raf"] ;
            return $this->params["runner-autopilot-file"] ; }
        else if (isset($this->params["runauto"])) {
            $this->params["runner-autopilot-file"] = $this->params["runauto"] ;
            return $this->params["runner-autopilot-file"] ; }
        else  if (isset($this->params["runnerauto"])) {
            $this->params["runner-autopilot-file"] = $this->params["runnerauto"] ;
            return $this->params["runner-autopilot-file"] ; }
        return false ;
    }

    protected function getAutopilotFileName(){
        if (isset($this->params["autopilot-file"])) { return $this->params["autopilot-file"] ; }
        else if (isset($this->params["af"])) {
            $this->params["autopilot-file"] = $this->params["af"] ;
            return $this->params["utopilot-file"] ; }
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

    protected function parseAvailableTools($tool){
        $all_tool_synonyms = array();
        $all_tool_synonyms[PTCCOMM] = array("ptconfigure", "configure", "config", "ptc", "ptconfigure.cmd") ;
        $all_tool_synonyms[PTBCOMM] = array("ptbuild", "build", "ptb", "ptbuild.cmd") ;
        $all_tool_synonyms[PTDCOMM] = array("ptdeploy", "deploy", "ptd", "ptdeploy.cmd") ;
        $all_tool_synonyms[PTVCOMM] = array("ptvirtualize", "virtualize", "ptv", "develop", "virtualize.cmd") ;
        $all_tool_synonyms[PTTRCOMM] = array("pttrack", "track", "pttr", "pttrack.cmd") ;
        $all_tool_synonyms[PTTECOMM] = array("pttest", "test", "ptte", "pttest.cmd") ;
        foreach ($all_tool_synonyms as $final_tool => $cur_synonyms) {
            if (in_array($tool, $cur_synonyms)) {
                $tool = $final_tool ; } }
        return $tool ;
    }

    protected function getNameOfModuleToRun(){
        if (isset($this->params["module"])) { return $this->params["module"] ; }
        else { $question = "Enter module name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getNameOfActionToRun(){
        if (isset($this->params["action"])) { return $this->params["action"] ; }
        else { $question = "Enter action name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getParametersToForward(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["params"])) {
            $res = $this->transformOurParams($this->params["params"], array("af", "autopilot-file")) ;
            $afn = $this->getRunAutopilotFileName() ;
            if ($afn !== false) {
                $file_only = basename($afn) ;
                $target_path = '/tmp/'.$file_only ;
                $logging->log("Automatically forwarding autopilot file parameter value of {$target_path}", $this->getModuleName());
                $res .= " --autopilot-file=".$target_path ; }
            return $res ; }
        else { $question = "Enter parameter string"; }
        $input = self::askForInput($question) ;
        $res = $this->transformOurParams($input) ;
        return $res ;
    }

    protected function transformOurParams($pstr, $drop_keys = array()) {
        $pairs = explode(",", $pstr) ;
        $parameter_string = "" ;
        foreach ($pairs as $pair) {
            if (strpos($pair, ":") !== false) {
                $key = substr($pair, 0, strpos($pair, ":") ) ;
                $val = substr($pair, strpos($pair, ":") + 1 ) ;
                if (!in_array($key, $drop_keys)) { $parameter_string .= " --{$key}={$val}" ; } }
            else {
                if (!in_array($key, $drop_keys)) { $parameter_string .= " --{$pair}" ; } } }
        return $parameter_string ;
    }

}