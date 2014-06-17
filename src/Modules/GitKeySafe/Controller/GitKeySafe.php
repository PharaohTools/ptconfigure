<?php

Namespace Controller ;

class GitKeySafe extends Base {


    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("config", "configure") )) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Configure") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->install();
            $this->content["appName"] = $thisModel->programNameInstaller ;
            return array ("type"=>"view", "view"=>"appInstall", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Git Key-Safe Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}