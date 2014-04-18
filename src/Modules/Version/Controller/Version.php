<?php

Namespace Controller ;

class Version extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="latest") {
            $this->content["versioningResult"] = $thisModel->askWhetherToVersionLatest();
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }
        if ($action=="rollback") {
            $this->content["versioningResult"] = $thisModel->askWhetherToVersionRollback();
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }
        if ($action=="specific") {
            $this->content["versioningResult"] = $thisModel->askWhetherToVersionSpecific();
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }

    }

}