<?php

Namespace Controller ;

class CukeConf extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $this->content["cukeConfResult"] = $thisModel->askWhetherToCreateCuke();
            return array ("type"=>"view", "view"=>"cukeConf", "pageVars"=>$this->content); }

        else if ($action=="reset") {
            $this->content["cukeConfResult"] = $thisModel->askWhetherToResetCuke();
            return array ("type"=>"view", "view"=>"cukeConf", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Cuke Configuration Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}