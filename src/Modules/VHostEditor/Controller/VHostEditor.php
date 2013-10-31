<?php

Namespace Controller ;

class VHostEditor extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) { return $isHelp; }
        $action = $pageVars["route"]["action"];

        $VhostEditorModel = new \Model\VHostEditor($pageVars["route"]["extraParams"]);

        if ($action=="list") {
            $this->content["VhostEditorResult"] = $VhostEditorModel->askWhetherToListVHost();
            return array ("type"=>"view", "view"=>"VhostEditor", "pageVars"=>$this->content); }

        if ($action=="add") {
            $this->content["VhostEditorResult"] = $VhostEditorModel->askWhetherToCreateVHost();
            return array ("type"=>"view", "view"=>"VhostEditor", "pageVars"=>$this->content); }

        else if ($action=="rm") {
            $this->content["VhostEditorResult"] = $VhostEditorModel->askWhetherToDeleteVHost();
            return array ("type"=>"view", "view"=>"VhostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid VHost Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}