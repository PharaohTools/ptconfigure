<?php

Namespace Controller ;

class UbuntuCompiler extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\UbuntuCompiler($pageVars["route"]["extraParams"]);
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}