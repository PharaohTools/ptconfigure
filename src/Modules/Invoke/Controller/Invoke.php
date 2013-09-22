<?php

Namespace Controller ;

class Invoke extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\DeveloperTools($pageVars["route"]["extraParams"]);
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
        $action = $pageVars["route"]["action"];

        if ($action=="cli") {

            $invSSHModel = new \Model\InvokeSSH($pageVars["route"]["extraParams"]);
            $this->content["shlResult"] = $invSSHModel->askWhetherToInvokeSSHShell();
            return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content); }

        if ($action=="script") {

            $invSSHModel = new \Model\InvokeSSH($pageVars["route"]["extraParams"]);
            $this->content["shlResult"] = $invSSHModel->askWhetherToInvokeSSHScript($pageVars["route"]["extraParams"]);

            return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content); }

        if ($action=="autopilot") {

            $autoPilotType= (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : null;

            if (isset($autoPilotType) && strlen($autoPilotType)>0 ) {

                $autoPilotFile = getcwd().'/'.escapeshellcmd($autoPilotType);
                $autoPilot = $this->loadAutoPilot($autoPilotFile);

                if ( $autoPilot!==null ) {

                    $invSSHModel = new \Model\InvokeSSH($pageVars["route"]["extraParams"]);
                    $this->content["invSshScriptResult"] = $invSSHModel->runAutoPilotInvokeSSHScript($autoPilot);
                    if ($autoPilot["sshInvokeSSHDataExecute"] && $this->content["invSshScriptResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Invoke SSH Script Broken";
                        return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content);  }

                    $this->content["invSshDataResult"] = $invSSHModel->runAutoPilotInvokeSSHData($autoPilot);
                    if ($autoPilot["sshInvokeSSHDataExecute"] && $this->content["invSshDataResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Invoke SSH Data Broken";
                        return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content);  } }


                else {
                        $this->content["autoPilotErrors"]="Auto Pilot not defined"; }  }

            else {
                $this->content["autoPilotErrors"]="Auto Pilot not defined"; }

            return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content); }

    }

    private function loadAutoPilot($autoPilotFile){
        if (file_exists($autoPilotFile)) {
            include_once($autoPilotFile); }
        $autoPilot = (class_exists('\Core\AutoPilot')) ? new \Core\AutoPilot() : null ;
        return $autoPilot;
    }

}