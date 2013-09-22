<?php

Namespace Controller ;

class MysqlServer extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\MysqlServer($pageVars["route"]["extraParams"]);
        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}