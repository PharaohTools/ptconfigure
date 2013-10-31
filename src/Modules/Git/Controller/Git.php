<?php

Namespace Controller ;

class Git extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action == "checkout" || $action == "co") {
            $gitCheckoutModel = new \Model\Git($pageVars["route"]["extraParams"]);
            $this->content["checkOutResult"] = $gitCheckoutModel->checkoutProject($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"git", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Git Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}