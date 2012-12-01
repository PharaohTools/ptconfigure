<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class Router {

    private	$route; // the attribute to be passed to the calling object
    private $modelRoute;
    private $availableRoutes;

    public function __construct() {
        $modelFactory = new \Core\ModelFactory() ;
        $this->modelRoute = $modelFactory->getModel("Router") ;
        $this->availableRoutes = $this->modelRoute->getAllowedRoutes() ;
    }

	/**
	* public function run
	* @description: This public method is called by the router and the only exposed function bar constructor
	*/
	public function run() {
		$this->setCurrentRoute();
        return $this->route ;
	}

	/**
	* private function setCurrentRoute
	* @description: This perform the functions to process the route requested into  an array of either the requested
    * route or. @todo In real world Extra xss defense would be called here, as could a url rewriting function.
	*/
	private function setCurrentRoute() {
        $defaultRoute = $this->getDefaultRoute();
        $this->setRouteController();
        if ($this->route != $defaultRoute ) { $this->setRouteAction(); }
	}

    /**
     * private function getDefaultRoute
     * @description:
     */
    private function getDefaultRoute() {
        $keys = array_keys($this->availableRoutes);
        return array( "control" => $keys[0] , "action" => $this->availableRoutes[$keys[0]][0] );
    }

    /**
     * private function setRouteController
     * @description:
     */
    private function setRouteController() {
        (isset($_REQUEST["control"]) && array_key_exists( $_REQUEST["control"], $this->availableRoutes ))
        ? $this->route["control"] = $_REQUEST["control"] : $this->route = $this->getDefaultRoute();
    }

    /**
     * private function setRouteAction
     * @description:
     */
    private function setRouteAction() {
        (isset($_REQUEST["action"])
            && array_key_exists($_REQUEST["control"], $this->availableRoutes)
            && in_array( $_REQUEST["action"], $this->availableRoutes[$_REQUEST["control"]] ))
        ? $this->route["action"] = $_REQUEST["action"] : $this->route = $this->getDefaultRoute();
    }

}