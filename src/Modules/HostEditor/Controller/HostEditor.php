<?php

Namespace Controller ;

class HostEditor extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $hostEditorModel = new \Model\HostEditor($pageVars["route"]["extraParams"]);

        if ($action=="add") {
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostEntry();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        else if ($action=="rm") {
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostRemoval();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Host Editor Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}