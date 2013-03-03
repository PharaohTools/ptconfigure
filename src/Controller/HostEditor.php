<?php

Namespace Controller ;

class HostEditor extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        if ($action=="add") {
            $hostEditorModel = new \Model\HostEditor();
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostEntry();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        else if ($action=="rm") {
            $hostEditorModel = new \Model\HostEditor();
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostRemoval();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Host Editor Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}