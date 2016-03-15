<?php

Namespace Controller ;

class DBInstall extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array("install"), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeDBInstall($pageVars) ; }

        if ($action=="install") {
            $this->content["result"] = $thisModel->askWhetherToInstallDB();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }
        else if ($action=="save") {
            $this->content["result"] = $thisModel->askWhetherToSaveDB();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }
        else if ($action=="drop") {
            $this->content["result"] = $thisModel->askWhetherToDropDB();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }
        else if ($action=="useradd") {
            $this->content["result"] = $thisModel->askWhetherToAddUser();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }
        else if ($action=="userdrop") {
            $this->content["result"] = $thisModel->askWhetherToDropUser();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }
        $this->content["messages"][] = "Invalid DB Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    protected function getExecutorForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeDBInstall"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
            $myDBInstallRoutes = (isset($info) && method_exists($info, "dbInstallActions")) ? $info->dbInstallActions() : array() ;
            if (in_array($action, $myDBInstallRoutes)) {
                return $controller ; } }
        return null ;
    }

}