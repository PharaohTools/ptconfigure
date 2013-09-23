<?php

Namespace Controller ;

class DapperfyJoomla extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $dapperfyModel = new \Model\DapperfyJoomla();

        if ($action=="create-joomla15") {
          $this->content["genCreateResult"] = $dapperfyModel->askWhetherToDapperfy();
          return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        if ($action=="create-joomla3x") {
          $this->content["genCreateResult"] = $dapperfyModel->askWhetherToDapperfy();
          return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}