<?php

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

require_once("autoLoad.php");
$routeObject = new \Core\Router();
$control = new \Core\Control();
$view = new \Core\View();
$route = $routeObject->run();
$viewVars = $control->executeControl($route["control"]);
$view->executeView($viewVars);
/*       */