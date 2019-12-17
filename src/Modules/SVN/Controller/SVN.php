<?php

Namespace Controller ;

class SVN extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action == "checkout" || $action == "co") {
            $this->content["result"] = $thisModel->checkoutProject($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"svn", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid SVN Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}