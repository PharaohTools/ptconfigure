<?php

Namespace Controller ;

class DBConfigure extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        $thisModel = new \Model\DBConfigure($pageVars["route"]["extraParams"]) ;

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $this->content["dbResult"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="reset") {
            $this->content["dbResult"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DB Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}