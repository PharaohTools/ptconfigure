<?php

Namespace Controller ;

class DBConfigure extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeDBConfigure($pageVars) ; }

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="reset") {
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DB Configure Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    protected function getExecutorForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeDBConfigure"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
            $myDBConfigureRoutes = (isset($info) && method_exists($info, "dbConfigureActions")) ? $info->dbConfigureActions() : array() ;
            if (in_array($action, $myDBConfigureRoutes)) {
                return $controller ; } }
        return null ;
    }

}