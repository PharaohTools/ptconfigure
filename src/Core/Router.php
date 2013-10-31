<?php

Namespace Core;

class Router {

    private	$bootstrapParams;
    private	$route;
    private $availableRoutes = array() ;

    public function run($bootstrapParams) {
      $this->bootstrapParams = $bootstrapParams;
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
      $all_processed_arrays = array();
      foreach ($allInfoObjects as $infoObject) {
        $curKeys = array_keys($infoObject->routesAvailable());
        $routesAvailable = $infoObject->routesAvailable();
        foreach ($curKeys as $curKey) {
          if (isset($all_processed_arrays[$curKey]) ) {
            $curValues = $all_processed_arrays[$curKey];
            $all_processed_arrays[$curKey] = array_merge($curValues, $routesAvailable[$curKey] ); }
          else {
            $all_processed_arrays[$curKey] = $routesAvailable[$curKey]; } } }
      $this->availableRoutes = $all_processed_arrays;
    }

    private function getDefaultRoute() {
      $this->setDefaultRouteExtraParams() ;
      return array( "control" => "Index" , "action" => "index", "extraParams" => $this->route["extraParams"] );
    }

    private function parseControllerAliases() {
      $allInfoObjects = AutoLoader::getInfoObjects() ;
      $aliases = array();
      foreach ($allInfoObjects as $infoObject) {
        $aliases = array_merge( $aliases, $infoObject->routeAliases() ); }
      if (isset($this->bootstrapParams[1])) {
        if (array_key_exists($this->bootstrapParams[1], $aliases)) {
          $this->bootstrapParams[1] = strtr($this->bootstrapParams[1], $aliases); } }
    }

    private function setRouteController() {
        (isset($this->bootstrapParams[1]) && array_key_exists( $this->bootstrapParams[1], $this->availableRoutes ))
            ? $this->route["control"] = $this->bootstrapParams[1] : $this->route = $this->getDefaultRoute();
    }

    private function setRouteAction() {
        $actionSet = isset($this->bootstrapParams[2]) ;
        $correctAct = ($actionSet) ? in_array( $this->bootstrapParams[2], $this->availableRoutes[$this->bootstrapParams[1]] ) : false ;
        ($actionSet && $correctAct) ? $this->route["action"] = $this->bootstrapParams[2] : $this->route = $this->getDefaultRoute();
    }

    private function setRouteExtraParams() {
        $this->route["extraParams"] = array();
        $numberOfExtraParams = count($this->bootstrapParams)-3;
        for ($i=3; $i<($numberOfExtraParams+3); $i++) {
            $this->route["extraParams"][] = $this->bootstrapParams[$i] ;}
    }

    private function setDefaultRouteExtraParams() {
        $this->route["extraParams"] = array();
        $numberOfExtraParams = count($this->bootstrapParams)-1;
        for ($i=1; $i<($numberOfExtraParams+1); $i++) {
            $this->route["extraParams"][] = $this->bootstrapParams[$i] ;}
    }

}