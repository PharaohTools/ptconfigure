<?php

Namespace Controller ;

class NginxSBEditor extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="list") {
            $this->content["NginxSBEditorResult"] = $thisModel->askWhetherToListServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="add") {
            $this->content["NginxSBEditorResult"] = $thisModel->askWhetherToCreateServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="remove" || $action=="rm") {
            $this->content["NginxSBEditorResult"] = $thisModel->askWhetherToDeleteServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="enable" || $action=="en") {
            $this->content["NginxSBEditorResult"] = $thisModel->askWhetherToEnableServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="disable" || $action=="dis") {
            $this->content["NginxSBEditorResult"] = $thisModel->askWhetherToDisableServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid ServerBlock Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}