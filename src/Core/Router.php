<?php			

Namespace Core;

class Router {

    private	$argv;
    private	$route;
    private $availableRoutes = array(
        "index" => array("index") ,		
        "invoke" => array("cli", "script", "autopilot") ,
        "version" => array("cli", "latest", "rollback", "specific") ,
        "setup" => array("dev-client") ,
        "checkout" => array("git") ,
        "hostEditor" => array("add", "rm") ,
        "VHostEditor" => array("add", "rm", "list") ,
        "cukeConf" => array("conf", "reset")  ,
        "database" => array("install", "drop", "configure", "config", "conf", "reset", "useradd", "userdrop")  ,
        "project" => array("init", "build-install", "container", "cont")  ,
        "install" => array("cli", "autopilot") );

	public function run($argv) {
        $this->argv = $argv;
		$this->setCurrentRoute();
        return $this->route ;
	}

	private function setCurrentRoute() {
        $defaultRoute = $this->getDefaultRoute();
        $this->parseControllerAliases();
        $this->setRouteController();
        if ($this->route != $defaultRoute ) {
            $this->setRouteAction();
            $this->setRouteExtraParams(); }
	}

    private function getDefaultRoute() {
        $keys = array_keys($this->availableRoutes);
        return array( "control" => $keys[0] , "action" => $this->availableRoutes[$keys[0]][0] );
    }

    private function parseControllerAliases() {
        $aliases = array("co"=>"checkout", "hosteditor"=>"hostEditor", "he"=>"hostEditor", "host"=>"hostEditor",
            "vhostEditor"=>"VHostEditor", "vhosteditor"=>"VHostEditor", "vhc"=>"VHostEditor", "cuke"=>"cukeConf",
            "cukeconf"=>"cukeConf", "proj"=>"project", "db"=>"database");
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
        $numberOfExtraParams = count($this->argv)-3;
        for ($i=3; $i<($numberOfExtraParams+3); $i++) {
            $this->route["extraParams"][] = $this->argv[$i] ;}
    }

}
