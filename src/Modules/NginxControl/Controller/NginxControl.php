<?php

Namespace Controller ;

class NginxControl extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("start", "stop", "restart"))) {
            $this->content["NginxControlResult"] = $thisModel->askWhetherToCtlNginx($action);
            return array ("type"=>"view", "view"=>"NginxControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Nginx Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}