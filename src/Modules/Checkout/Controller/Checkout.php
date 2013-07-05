<?php

Namespace Controller ;

class Checkout extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action == "git") {
            $gitCheckoutModel = new \Model\CheckoutGit();
            $this->content["checkOutResult"] = $gitCheckoutModel->checkoutProject($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"checkout", "pageVars"=>$this->content); }
        $this->content["messages"][] = "Invalid Checkout Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}