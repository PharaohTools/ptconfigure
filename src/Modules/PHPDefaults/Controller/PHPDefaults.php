<?php

Namespace Controller ;

class PHPDefaults extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];
        $this->content["route"] = $pageVars["route"] ;

        if ($action=="restart") {
            $this->content["result"] = $thisModel->restartPHPDefaults();
            return array ("type"=>"view", "view"=>"appInstall", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}