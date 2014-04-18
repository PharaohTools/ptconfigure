<?php

Namespace Controller ;

class AppSettings extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="set") {
          $this->content["configResult"] = $thisModel->askWhetherToSetConfig();
          return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="get") {
            $this->content["configResult"] = $thisModel->askWhetherToGetConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="delete") {
            $this->content["configResult"] = $thisModel->askWhetherToDeleteConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="list") {
            $this->content["configResult"] = $thisModel->askWhetherToListConfigs();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}