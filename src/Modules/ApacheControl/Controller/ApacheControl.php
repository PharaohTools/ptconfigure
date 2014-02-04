<?php

Namespace Controller ;

class ApacheControl extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="start") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        if ($action=="stop") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStopApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        else if ($action=="restart") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToRestartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Apache Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}