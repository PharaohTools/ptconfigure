<?php

Namespace Controller ;

class Index extends Base {

    public function execute($pageVars) {
      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];
      $indexModel = new \Model\Index($pageVars["route"]["extraParams"]);
      $this->content["modulesInfo"] = $indexModel->findAllModuleNames();
      return array ("type"=>"view", "view"=>"index", "pageVars"=>$this->content);
    }

}