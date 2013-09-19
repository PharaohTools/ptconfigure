<?php

Namespace Controller ;

class Cleofy extends Base {

    private $injectedActions = array();

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];
        $params = $pageVars["route"]["extraParams"];

        $cleofyModel = new \Model\Cleofy($params);

        if ($action=="standard") {
          $this->content["genCreateResult"] = $cleofyModel->askWhetherToCleofy();
          return array ("type"=>"view", "view"=>"cleofy", "pageVars"=>$this->content); }

        else if (in_array($action, array_keys($this->injectedActions))) {
          $extendedModel = new $this->injectedActions[$action]() ;
          $this->content["genCreateResult"] = $extendedModel->askWhetherToCleofy();
          return array ("type"=>"view", "view"=>"cleofy", "pageVars"=>$this->content);
        }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function injectCleofyAction($action, $modelName) {
       $this->injectedActions[] = array($action => $modelName);
    }

}