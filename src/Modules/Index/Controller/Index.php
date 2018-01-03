<?php

Namespace Controller ;

class Index extends Base {

    public function execute($pageVars) {
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        $this->content = $pageVars ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $this->content["quiet"] = $thisModel->isQuiet();
        if ($this->content["quiet"] == false) {
            $this->content["modulesInfo"] = $thisModel->findModuleNames($pageVars["route"]["extraParams"]);
        }
        return array ("type"=>"view", "view"=>"index", "pageVars"=>$this->content);
    }

}