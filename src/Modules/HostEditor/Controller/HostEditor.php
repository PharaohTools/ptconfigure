<?php

Namespace Controller ;

class HostEditor extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
         $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
         if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
        $action = $pageVars["route"]["action"];

        if ($action=="add") {
            $this->content["result"] = $thisModel->askWhetherToDoHostEntry();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        else if ($action=="rm") {
            $this->content["result"] = $thisModel->askWhetherToDoHostRemoval();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Host Editor Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}