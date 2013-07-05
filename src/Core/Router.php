<?php			

Namespace Core;

class Router {

    private	$argv;
    private	$route;
    private $availableRoutes = array() ;

    public function run($argv) {
        $this->argv = $argv;
        $this->setCurrentRoute();
        return $this->route ;
    }

    private function setCurrentRoute() {
        $this->getAvailableRoutes();
        $defaultRoute = $this->getDefaultRoute();
        $this->parseControllerAliases();
        $this->setRouteController();
        if ($this->route != $defaultRoute ) {
            $this->setRouteAction();
            $this->setRouteExtraParams(); }
    }

    private function getAvailableRoutes() {
        $allInfoObjects = AutoLoader::getInfoObjects() ;
        foreach ($allInfoObjects as $infoObject) {
            $this->availableRoutes = array_merge( $this->availableRoutes, $infoObject->routesAvailable() ); }
    }

    private function getDefaultRoute() {
        return array( "control" => "Index" , "action" => "index" );
    }

    private function parseControllerAliases() {
        $allInfoObjects = AutoLoader::getInfoObjects() ;
        $aliases = array();
        foreach ($allInfoObjects as $infoObject) {
            $aliases = array_merge( $aliases, $infoObject->routeAliases() ); }
        if (isset($this->argv[1])) {
            if (array_key_exists($this->argv[1], $aliases)) {
                $this->argv[1] = strtr($this->argv[1], $aliases); } }
    }

    private function setRouteController() {
        (isset($this->argv[1]) && array_key_exists( $this->argv[1], $this->availableRoutes ))
            ? $this->route["control"] = $this->argv[1] : $this->route = $this->getDefaultRoute();
    }

    private function setRouteAction() {
        $actionSet = isset($this->argv[2]) ;
        $correctAct = in_array( $this->argv[2], $this->availableRoutes[$this->argv[1]] ) ;
        ($actionSet && $correctAct) ? $this->route["action"] = $this->argv[2] : $this->route = $this->getDefaultRoute();
    }

    private function setRouteExtraParams() {
        $this->route["extraParams"] = array();
        $numberOfExtraParams = count($this->argv)-3;
        for ($i=3; $i<($numberOfExtraParams+3); $i++) {
            $this->route["extraParams"][] = $this->argv[$i] ;}
    }

}
