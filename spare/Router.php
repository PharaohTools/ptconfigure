<?php

Namespace Model;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class Router {

	private	$allowedRoutes; // The allowed Routes Array

	public function __construct() {
	    $this->populateAllowedRoutes();
	}

	/**
	* public function getAllowedRoutes
	* @desc: This public method is the only method exposed, and is called by the router
	*/
	public function getAllowedRoutes() {
        return $this->allowedRoutes;
	}

	/**
	* private function populateAllowedRoutes
	* @desc: This private method populates the array of allowed routes
	*/
	private function populateAllowedRoutes() {
        $this->allowedRoutes = array(
            "index" => array( "index" ) ,
            "page" => array( "group" , "results" ) ,
            "register" => array( "register" ) ,
            "login" => array( "login" , "logout")
        );
	}

}