<?php

Namespace Controller ;

class Index extends Base {

    public function execute($pageVars) {
        return array ("type"=>"view", "view"=>"index", "pageVars"=>$this->content);
    }

}