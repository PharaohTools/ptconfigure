<?php

Namespace Controller ;

class EnvironmentConfig extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $environmentConfigModel = new \Model\EnvironmentConfig();

        if ($action=="configure" || $action=="config") {
            $this->content["result"] = $environmentConfigModel->askWhetherToEnvironmentConfig();
            return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        if ($action=="list") {
            $this->content["result"] = $environmentConfigModel->askWhetherToListConfig();
            return array ("type"=>"view", "view"=>"environmentConfigList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}