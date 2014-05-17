<?php

Namespace Controller ;

class Drupal extends Base {

    public function executeBuilderfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "drupal") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DrupalContinuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Drupal Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}