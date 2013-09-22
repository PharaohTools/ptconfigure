<?php

Namespace Controller ;

class Cleofy extends Base {

    private $injectedActions = array();

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];
        $params = $pageVars["route"]["extraParams"];
        $thisModel = new \Model\Cleofy($params);

        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($action=="standard") {
          $this->content["genCreateResult"] = $thisModel->askWhetherToCleofy();
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