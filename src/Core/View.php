<?php

Namespace Core;

class View {

    private $viewHelpers;

    public function __construct(){
        $this->viewHelpers = new ViewHelpers();
    }

    public function executeView($view, Array $viewVars) {
        $viewVars["layout"] = (isset($viewVars["layout"])) ? $viewVars["layout"] : "default" ;
        $templateData = $this->loadTemplate ($view, $viewVars) ;
        $data = $this->loadLayout ( $viewVars["layout"], $templateData, $viewVars) ;
        $this->renderAll($data) ;
    }

    private function loadLayout ($layout, $templateData, Array $pageVars) {
        ob_start();
        $layoutFileFullPath = dirname(__FILE__).'/../Views/'.ucfirst($layout)."Layout.tpl.php";
        if ( file_exists($layoutFileFullPath )) require_once($layoutFileFullPath);
        return ob_get_clean();
    }

    private function loadTemplate ($view, Array $pageVars) {
        ob_start();
        $viewFileFullPath =  dirname(__FILE__).'/../Views/'.ucfirst($view)."View.tpl.php";
        if ( file_exists($viewFileFullPath )) require_once($viewFileFullPath);
        return ob_get_clean();
    }

    private function renderAll($processedData) {
        echo $processedData;
    }

}