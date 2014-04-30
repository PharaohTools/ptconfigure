<?php

Namespace Controller ;

class Builderfy extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("developer", "staging", "production", "continuous"))) {
          $thisModel->params["action"] = $action ;
          $this->content["result1"] = $thisModel->askInstall();
          $this->content["result2"] = $thisModel->result;
          return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}