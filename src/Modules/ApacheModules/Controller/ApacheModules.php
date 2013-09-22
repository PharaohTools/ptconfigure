<?php

Namespace Controller ;

class ApacheModules extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\ApacheModules($pageVars["route"]["extraParams"]);

        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}