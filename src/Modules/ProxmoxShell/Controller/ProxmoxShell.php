<?php

Namespace Controller ;

class ProxmoxShell extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if (in_array($action, ['get', 'create', 'set', 'ls', 'delete'])) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ShellCommand") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askWhetherToListData($action);
            return array ("type"=>"view", "view"=>"proxmoxAPI", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}