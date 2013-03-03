<?php

Namespace Controller ;

class Checkout extends Base {

    public function execute($pageVars) {
        if ($pageVars["route"]["action"] == "git") {

            $gitCheckoutModel = new \Model\GitCheckout();
            $this->content["checkOutResult"] = $gitCheckoutModel->checkoutProject($pageVars["route"]["extraParams"][0]);

            return array ("type"=>"view", "view"=>"checkout", "pageVars"=>$this->content); }
        $this->content["messages"] = "Invalid Checkout Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}