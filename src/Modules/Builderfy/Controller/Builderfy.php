<?php

Namespace Controller ;

class Builderfy extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeBuilderfy($pageVars) ; }

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($action == "developer") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Developer") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if ($action == "continuous") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Continuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if (in_array($action, array("staging", "production"))) {
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Builderfy Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    protected function getExecutorForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeBuilderfy"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
                $myBuilderfyRoutes = (isset($info) && method_exists($info, "builderfyActions")) ? $info->builderfyActions() : array() ;
                if (in_array($action, $myBuilderfyRoutes)) {
                    return $controller ; } }
        return null ;
    }

}