<?php

Namespace Controller ;

class EnvironmentConfig extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\EnvironmentConfig($pageVars["route"]["extraParams"]);
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action=="config") {
          $this->content["result"] = $thisModel->askWhetherToEnvironmentConfig();
          return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}