<?php

Namespace Core ;

class Control {

    public function executeControl($control, $pageVars) {
        $className = '\\Controller\\'.ucfirst($control);
        $controlObject = new $className;
        $res = $controlObject->execute($pageVars);
        return $res ;
    }

}