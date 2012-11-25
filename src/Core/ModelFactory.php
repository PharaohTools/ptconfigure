<?php

Namespace Core ;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class ModelFactory {
    /**
	* public function getModel
	* @desc: This public method is the only method exposed, and returns the requested model object
	*/
    public function getModel($modelRequested) {
        $modelRequested = ucfirst($modelRequested);
	    $className = '\\Model\\'.$modelRequested;
        return new $className;
    }
}