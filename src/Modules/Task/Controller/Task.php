<?php

Namespace Controller ;

class Task extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $taskFileExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeTask($pageVars) ; }

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeTask($pageVars) ; }

        if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        if (in_array($action, array("list") )) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askAction($action);
            $this->content["appName"] = $thisModel->programNameInstaller ;
            return array ("type"=>"view", "view"=>"TaskList", "pageVars"=>$this->content); }

    }

    protected function getTaskfileTaskForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeDapperfy"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
            $myDapperfyRoutes = (isset($info) && method_exists($info, "dapperfyActions")) ? $info->dapperfyActions() : array() ;
            if (in_array($action, $myDapperfyRoutes)) {
                return $controller ; } }
        return null ;
    }

    protected function getExecutorForAction($action) {
        $controllers = \Core\AutoLoader::getAllControllers() ;
        foreach ($controllers as $controller) {
            if (method_exists($controller, "executeDapperfy"))
                $info = \Core\AutoLoader::getSingleInfoObject(substr(get_class($controller), 11)) ;
            $myDapperfyRoutes = (isset($info) && method_exists($info, "dapperfyActions")) ? $info->dapperfyActions() : array() ;
            if (in_array($action, $myDapperfyRoutes)) {
                return $controller ; } }
        return null ;
    }

}