<?php

Namespace Controller ;

class Index extends Base {

    public function execute($pageVars) {
        parent::initUser($pageVars);
        return array ("type"=>"view", "view"=>"index", "pageVars"=>$this->content);
    }

}