<?php

Namespace Controller ;

class VHostEditor extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
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