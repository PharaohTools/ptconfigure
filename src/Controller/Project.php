<?php

Namespace Controller ;

class HostEditor extends Base {

    public function execute($pageVars) {

            $hostEditorModel = new \Model\HostEditor();
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostEntry();
            return array ("type"=>"view", "view"=>"hostEditor", "pageVars"=>$this->content);

        $this->content["messages"] = "Invalid Host Editor Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}