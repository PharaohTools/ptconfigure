<?php

Namespace Controller ;

class DbConfigure extends Base {

    public function execute($pageVars) {

        $dbConfigureModel = new \Model\DBConfigure();
        $this->content["dbConfigureResult"] = $dbConfigureModel->askWhetherToDoHostEntry();
        return array ("type"=>"view", "view"=>"dbConfigure", "pageVars"=>$this->content);

        $this->content["messages"] = "Invalid DB Configure Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}