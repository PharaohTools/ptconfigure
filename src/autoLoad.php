<?php

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

function __autoload($className) {
    $className = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $className.'.php';
    if (is_readable($filename)) { require_once $filename; }
}