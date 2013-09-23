<?php

Namespace Controller ;

class Dapperfy extends Base {

    private $injectedActions = array();

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $dapperfyModel = new \Model\Dapperfy();

        if ($action=="standard") {
          $this->content["genCreateResult"] = $dapperfyModel->askWhetherToDapperfy();
          return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        else if (in_array($action, array_keys($this->injectedActions))) {
          $extendedModel = new $this->injectedActions[$action]() ;
          $this->content["genCreateResult"] = $extendedModel->askWhetherToDapperfy();
          return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content);
        }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function injectDapperfyAction($action, $modelName) {
       $this->injectedActions[] = array($action => $modelName);
    }

}