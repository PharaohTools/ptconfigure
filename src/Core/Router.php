<?php

Namespace Core;

class Router {

    private	$route; // the attribute to be passed to the calling object
    private $availableRoutes = array(
            "index" => array( "index" ) ,
            "page" => array( "group" , "results" ) ,
            "register" => array( "register" ) ,
            "login" => array( "login" ),
            "logout" => array( "logout")
        );

	public function run() {
		$this->setCurrentRoute();
        return $this->route ;
	}

	/**  @todo In real world Extra xss defense would be called here, as could a url rewriting function. */
	private function setCurrentRoute() {
        $defaultRoute = $this->getDefaultRoute();
        $this->setRouteController();
        if ($this->route != $defaultRoute ) { $this->setRouteAction(); }
	}

    private function getDefaultRoute() {
        $keys = array_keys($this->availableRoutes);
        return array( "control" => $keys[0] , "action" => $this->availableRoutes[$keys[0]][0] );
    }

    private function setRouteController() {
        (isset($_REQUEST["control"]) && array_key_exists( $_REQUEST["control"], $this->availableRoutes ))
        ? $this->route["control"] = $_REQUEST["control"] : $this->route = $this->getDefaultRoute();
    }

    private function setRouteAction() {
        $correctControl = array_key_exists($_REQUEST["control"], $this->availableRoutes) ;
        $actionSet = isset($_REQUEST["action"]) ;
        $actionExists = isset($this->availableRoutes[$_REQUEST["control"]]);
        $correctAction = ($actionExists && in_array( $_REQUEST["action"], $this->availableRoutes[$_REQUEST["control"]] ) );
        ($correctControl && $actionSet && $correctAction)
        ? $this->route["action"] = $_REQUEST["action"] : $this->route = $this->getDefaultRoute();
    }

}