<?php

Namespace Core ;

class Control {

    public function executeControl($control, $pageVars) {
        $className = '\\Controller\\'.ucfirst($control);
        $controlObject = new $className;
        return $controlObject->execute($pageVars);
    }

}