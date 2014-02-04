<?php

Namespace Controller ;

class Dapperfy extends Base {

    private $injectedActions = array();

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        $dapperfyModel = new \Model\Dapperfy($pageVars["route"]["extraParams"]);

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