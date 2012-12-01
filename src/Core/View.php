<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class View {

    public function executeView(Array $viewVars) {
        try {
            $viewVars["layout"] = (isset($viewVars["layout"])) ? $viewVars["layout"] : "default" ;
            $templateData = $this->loadTemplate ($viewVars["view"], $viewVars["pageVars"]) ;
            $data = $this->loadLayout ( $viewVars["layout"], $templateData, $viewVars["pageVars"]) ;
            $this->renderAll($data) ;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    private function loadLayout ($layout, $templateData, Array $pageVars) {
        ob_start();
        $layoutFileName = ucfirst($layout)."Layout.tpl.php";
        $layoutFileFullPath = dirname(__FILE__).'/../views/'.$layoutFileName ;
        if ( file_exists($layoutFileFullPath )) require_once($layoutFileFullPath);
        return ob_get_clean();
    }

    private function loadTemplate ($view, Array $pageVars) {
        ob_start();
        $viewFileName = ucfirst($view)."View.tpl.php";
        $viewFileFullPath =  dirname(__FILE__).'/../views/'.ucfirst($view)."View.tpl.php";
        if ( file_exists($viewFileFullPath )) require_once($viewFileFullPath);
        return ob_get_clean();
    }

    private function renderAll($processedData) {
        echo $processedData;
    }

}