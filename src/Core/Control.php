<?php

Namespace Core ;

class Control {

    private $modelFactory ;

    public function __construct() {
        $this->modelFactory = new ModelFactory();
    }

    public function executeControl($control, $pageVars) {
        $className = '\\Controller\\'.ucfirst($control);
        $controlObject = new $className;
        return $controlObject->execute($pageVars);
    }

}