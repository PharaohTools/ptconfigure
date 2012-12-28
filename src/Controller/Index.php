<?php

Namespace Controller ;

class Index extends Base {

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        return array ("type"=>"view", "view"=>"index", "pageVars"=>$this->content);
    }

}