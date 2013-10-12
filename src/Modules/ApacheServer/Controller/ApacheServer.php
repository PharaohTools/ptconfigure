<?php

Namespace Controller ;

class ApacheServer extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies("ApacheServer", $pageVars) ;
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}