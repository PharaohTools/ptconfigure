<?php

Namespace Model;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class Config {

    private	$appConfigVars; // The app config vars array
    private	$userConfigVars; // The user config vars Array

	public function __construct() {
        $this->populateAppConfigVars();
        $this->populateUserConfigVars();
	}

    /**
     * public function getConfigVars
     * @desc: This public method returns the configuration variables for the config class
     */
    public function getAppConfigVars() {
        return $this->appConfigVars;
    }

    /**
     * public function getConfigVars
     * @desc: This public method returns the configuration variables for the config class
     */
    public function getUserConfigVars() {
        return $this->userConfigVars;
    }

    /**
     * private function populateAppConfigVars
     * @desc: This private method populates the array of allowed routes
     */
    private function populateAppConfigVars() {
        $this->appConfigVars["title_main"] = "Ebay Code Practice" ;
        $this->appConfigVars["subtitle_home"] = "Practice Home Page" ;
        $this->appConfigVars["subtitle_configure"] = "Configure Page" ;
        $this->appConfigVars["home_intro_text"] = "Code Practice" ;
        $this->appConfigVars["config_title"] = "Configure Application" ;
        $this->appConfigVars["config_intro_text"] = "Configure the Application here" ;
    }

    /**
     * private function populateUserConfigVars
     * @desc: This private method populates the array of allowed routes
     */
    private function populateUserConfigVars() {
        $this->userConfigVars[] = array("idString"=>"hide_php_errors",
            "title"=>"Hide PHP errors", 
            "type"=>"text", 
            "description"=>"Hide PHP generated errors in this Application.");
    }

}