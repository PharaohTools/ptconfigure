<?php

Namespace Controller ;

class Dapperfy extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        $otherModuleExecutor = $this->getExecutorForAction($action);
        if (!is_null($otherModuleExecutor)) {
            return $otherModuleExecutor->executeDapperfy($pageVars) ; }

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($action=="standard") {
          $this->content["result"] = $thisModel->askWhetherToDapperfy();
          return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Dapperfy Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
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