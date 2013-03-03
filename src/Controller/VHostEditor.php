<?php

Namespace Controller ;

class VHostEditor extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        if ($action=="add") {
            $VhostEditorModel = new \Model\VHostEditor();
            $this->content["VhostEditorResult"] = $VhostEditorModel->askWhetherToCreateVHost();
            return array ("type"=>"view", "view"=>"VhostEditor", "pageVars"=>$this->content); }

        else if ($action=="rm") {
            $VhostEditorModel = new \Model\VHostEditor();
            $this->content["VhostEditorResult"] = $VhostEditorModel->askWhetherToDeleteVHost();
            return array ("type"=>"view", "view"=>"VhostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid VHost Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}