<?php

Namespace Controller ;

class CMSConfigure extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeCMSConfigure($pageVars) ; }

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $this->content["dbResult"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="reset") {
            $this->content["dbResult"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DB Configure Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    protected function getExecutorForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeCMSConfigure"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
            $myCMSConfigureRoutes = (isset($info) && method_exists($info, "cmsConfigureActions")) ? $info->cmsConfigureActions() : array() ;
            if (in_array($action, $myCMSConfigureRoutes)) {
                return $controller ; } }
        return null ;
    }

}