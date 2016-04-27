<?php

Namespace Controller ;

class User extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];

        if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        if (in_array($action, array("create", "remove", "set-password", "exists", "show-groups", "add-to-group", "remove-from-group") )) {
            $this->content["result"] = $thisModel->askAction($action);
            $this->content["appName"] = $thisModel->programNameInstaller ;
            $this->content["pageVars"] = $pageVars ;
            //var_dump("1", $pageVars) ;
            $this->content["params"] = $thisModel->params ;
           // var_dump("2", $this->content["params"]) ;
            return array ("type"=>"view", "view"=>"user", "pageVars"=>$this->content); }

    }

}
