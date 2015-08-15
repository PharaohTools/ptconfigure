<?php

Namespace Controller ;

class PTConfigureRequired extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}
