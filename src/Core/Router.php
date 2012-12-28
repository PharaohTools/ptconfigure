<?php

Namespace Core;

class Router {

    /*
     * todo expand availableroutes array or move to model, needs extra field of friendly route for action, all urls
     * can be easily made friendly with one line in htaccess. http://forums.phpfreaks.com/topic/237372-
     * friendly-urls-from-a-database/
     */
    private	$route; // the attribute to be passed to the calling object
    private $availableRoutes = array(
        "index" => array("index") ,
        "register" => array("register") ,
        "login" => array("login"),
        "logout" => array("logout"),
        "userPage" => array("user"),
        "groupPage" => array("group"),
        "resultsPage" => array("results"),
        "administerPermissions" => array("index","save")
    );

	public function run() {
		$this->setCurrentRoute();
        return $this->route ;
	}

	/**  @todo In real world Extra xss defense would be called here, as could a url rewriting function.
     */
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