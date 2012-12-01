<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class autoLoader{

    public function launch() {
        spl_autoload_register('Core\autoLoader::autoLoad');
    }

    public static function autoLoad($className) {
        $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
        if (is_readable($filename)) require_once $filename;
    }

}