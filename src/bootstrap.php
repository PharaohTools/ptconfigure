<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

$bootStrap = new bootStrap();
$bootStrap->main();

class bootStrap {

    public function __construct() {
        require_once("autoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

    public function main() {
        $routeObject = new \Core\Router();
        $route = $routeObject->run();
        $emptyPageVars = array("messages"=>array());
        $this->executeControl($route["control"], $emptyPageVars);
    }

    private function executeControl($controlToExecute, $pageVars=null) {
        $control = new \Core\Control();
        $controlResult = $control->executeControl($controlToExecute, $pageVars);
        try {
            if ($controlResult["type"]=="view") {
                $this->executeView( $controlResult["view"], $controlResult["pageVars"] );
            } else if ($controlResult["type"]=="control") {
                $this->executeControl( $controlResult["control"], $controlResult["pageVars"] );
            }
        } catch (\Exception $e) {
            throw new \Exception( 'No controller result type specified', 0, $e);
        }
    }

    private function executeView($viewTemplate, $viewVars) {
        $view = new \Core\View();
        $view->executeView($viewTemplate, $viewVars);
    }

}