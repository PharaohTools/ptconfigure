<?php

Namespace Controller ;

class Checkout extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        if ($pageVars["route"]["action"] == "git") {
            $gitCheckoutModel = new \Model\GitCheckout();
            $this->content["checkOutResult"] = $gitCheckoutModel->checkoutProject($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"checkout", "pageVars"=>$this->content); }
        $this->content["messages"][] = "Invalid Checkout Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}