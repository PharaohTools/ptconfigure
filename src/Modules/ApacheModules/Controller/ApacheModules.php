<?php

Namespace Controller ;

class ApacheModules extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies("ApacheModules", $pageVars) ;

        if (is_array($thisModel)) { // if we don't have an object, its an array of errors
            foreach($thisModel as $item) { $this->content["messages"][] = $item ; }
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }

        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

    }

}