<?php

Namespace Controller ;

class SystemDetectionGenerate extends Base {

    public function execute($pageVars) {

        $thisModel = new \Model\SystemDetectionGenerateAllOS($pageVars["route"]["extraParams"]);

        $isDefaultAction = parent::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        if ($pageVars["route"]["action"] === "generate-defaults") {
            $this->content["data"] = $thisModel->generate();
            return array ("type"=>"view", "view"=>"systemDetectionGenerate", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}