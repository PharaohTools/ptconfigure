<?php

Namespace Controller ;

class EnvironmentConfig extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action=="config") {
          $this->content["result"] = $thisModel->askWhetherToEnvironmentConfig();
          return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

    }

}