<?php

Namespace Core ;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class Control {

    private $modelFactory ;

    public function __construct() {
        $this->modelFactory = new ModelFactory();
    }

    public function executeControl($control) {
        $className = '\\Controller\\'.ucfirst($control);
        $controlObject = new $className;
        return $controlObject->execute();
    }

}