<?php

Namespace Controller ;

class SystemDetection extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\SystemDetectionAllOS($pageVars["route"]["extraParams"]);

        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($pageVars["route"]["action"]=="detect") {
            $this->content["result"] = $thisModel;
            return array ("type"=>"view", "view"=>"systemDetection", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}