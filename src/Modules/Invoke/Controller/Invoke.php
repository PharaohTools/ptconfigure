<?php

Namespace Controller ;

class Invoke extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];

        if ($action=="cli") {
            $this->content["shlResult"] = $thisModel->askWhetherToInvokeSSHShell();
            return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content); }
        if ($action=="script") {
            $this->content["shlResult"] = $thisModel->askWhetherToInvokeSSHScript();
            return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content); }
        if ($action=="data") {
            $this->content["shlResult"] = $thisModel->askWhetherToInvokeSSHData();
            return array ("type"=>"view", "view"=>"invoke", "pageVars"=>$this->content); }

    }

}