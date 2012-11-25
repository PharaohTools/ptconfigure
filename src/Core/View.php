<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class View {

    public function executeView($viewVars) {
        $data = (!isset($viewVars["layout"]))
                ? $this->processView($viewVars["view"], $viewVars["pageVars"]) : $this->processView($viewVars["view"], $viewVars["pageVars"], $viewVars["layout"]);
        $this->renderAll($data) ;
    }

    private function processView ($view, $pageVars, $layout="default") {
        $templateData = $this->loadTemplate ($view, $pageVars) ;
        return $this->loadLayout ($layout, $templateData, $pageVars) ;
    }

    private function loadLayout ($layout, $templateData, $pageVars) {
        ob_start();
        $layoutFileName = ucfirst($layout)."Layout.tpl.php";
        $layoutFileFullPath = dirname(__FILE__).'/../views/'.$layoutFileName ;
        if ( file_exists($layoutFileFullPath )) require_once($layoutFileFullPath);
        return ob_get_clean();
    }

    private function loadTemplate ($view, $pageVars) {
        ob_start();
        $viewFileName = ucfirst($view)."View.tpl.php";
        $viewFileFullPath =  dirname(__FILE__).'/../views/'.$viewFileName ;
        if ( file_exists($viewFileFullPath )) require_once($viewFileFullPath);
        return ob_get_clean();
    }

    private function renderAll($renderedData) {
        echo $renderedData;
    }

}