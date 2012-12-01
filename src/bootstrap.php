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
        $control = new \Core\Control();
        $viewVars = $control->executeControl($route["control"]);
        $view = new \Core\View();
        $view->executeView($viewVars);
    }

}