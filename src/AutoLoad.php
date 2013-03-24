<?php

Namespace Core;

class AutoLoader{

    public function launch() {
        spl_autoload_register('Core\autoLoader::autoLoad');
    }

    public static function autoLoad($className) {
        $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
        if (is_readable($filename)) require_once $filename;
    }

}