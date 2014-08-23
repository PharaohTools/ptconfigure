<?php

Namespace Controller ;

class PapyrusEditor extends Base {

    public function execute($pageVars) {

        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        if ($action=="start") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Editor") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["params"] = $thisModel->params;
            $this->setLoadOrSave($thisModel) ;
            $this->content["layout"] = "PapyrusEditorHTML" ;
            return array ("type"=>"view", "view"=>"papyrusEditor", "pageVars"=>$this->content); }

        if ($action=="install-interface") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "InstallPapyrusEditorInterface") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["appInstall"] = $thisModel->askWhetherToInstallInterface();
            return array ("type"=>"view", "view"=>"gameBlocksInterface", "pageVars"=>$this->content); }

        if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        return null ;
    }

    private function setLoadOrSave(&$thisModel) {
        if (isset($_REQUEST["doLoad"]) && $_REQUEST["doLoad"] == "on") { $this->content["current_papyrus"] = $thisModel->getPapyrus(); }
        if (isset($_REQUEST["doSave"]) && $_REQUEST["doSave"] == "on") { $this->content["saved_papyrus"] = $thisModel->savePapyrus(); }
    }

}