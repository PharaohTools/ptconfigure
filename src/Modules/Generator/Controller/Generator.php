<?php

Namespace Controller ;

class Generator extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $thisModel = new \Model\Generator($pageVars["route"]["extraParams"]);

        if ($action=="create") {
          $this->content["params"] = $thisModel->params;
          $this->content["genCreateResult"] = $thisModel->askWhetherToCreateAutoPilot();
          return array ("type"=>"view", "view"=>"generator", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}