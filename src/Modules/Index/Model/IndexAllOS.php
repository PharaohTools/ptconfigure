<?php

Namespace Model;

class IndexAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function findModuleNames($params) {
        if (isset($this->params["compatible-only"]) && $this->params["compatible-only"]=="true") {
            return $this->findOnlyCompatibleModuleNames($params); }
        if (isset($this->params["only-compatible"]) && $this->params["only-compatible"]=="true") {
            return $this->findOnlyCompatibleModuleNames($params); }
        return $this->findAllModuleNames() ;
    }

    private function findAllModuleNames() {
        $allInfoObjects = \Core\AutoLoader::getInfoObjects() ;
        $moduleNames = array() ;
        foreach ($allInfoObjects as $infoObject) {
            $array_keys = array_keys($infoObject->routesAvailable()) ;
            $miniRay = array() ;
            $miniRay["command"] = $array_keys[0] ;
            $miniRay["name"] = $infoObject->name ;
            $miniRay["hidden"] = $infoObject->hidden ;
            $moduleNames[] = $miniRay ; }
        return $moduleNames;
    }

    private function findOnlyCompatibleModuleNames($params) {
        $allModules = $this->findAllModuleNames() ;
        $controllerBase = new \Controller\Base();
        $errors = $controllerBase->checkForRegisteredModels($params, $allModules) ;
        $compatibleModules = array();
        foreach($allModules as $oneModule) {
            if (!in_array($oneModule["command"], $errors)) {
                $compatibleModules[] = $oneModule ; } }
        return $compatibleModules;
    }

}