<?php

Namespace Controller ;

class Dapperstrano extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies("Dapperstrano", $pageVars) ;
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}