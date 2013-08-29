<?php

Namespace Controller ;

class Generator extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $generatorModel = new \Model\Generator();

        if ($action=="create") {
          $this->content["genCreateResult"] = $generatorModel->askWhetherToCreateAutoPilot();
          return array ("type"=>"view", "view"=>"generator", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}