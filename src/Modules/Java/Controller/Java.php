<?php

Namespace Controller ;

class Java extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\Java($pageVars["route"]["extraParams"]);
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}